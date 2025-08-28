#!/bin/bash
# -----------------------------------------------------------------------------
# Script Name: certgen.sh
# Description:
#   This script automates the generation of a root Certificate Authority (CA),
#   private keys, X.509 certificates, and Diffie-Hellman parameters for various
#   services including nginx, redis, rabbitmq, and postgres.
#
#   All generated files are stored within the 'certs' directory.
#
# Usage:
#   ./certgen.sh [options]
#
# Options:
#   -h, --help      Display this help message and exit
#
# Details:
#   - Creates a 4096-bit RSA root CA key and self-signed certificate valid for 10 years.
#   - Generates 2048-bit RSA private keys and signed certificates for each service.
#   - Creates Diffie-Hellman parameters (2048 bits) for enhanced TLS security.
#   - Uses external OpenSSL config file with extensions.
#   - Loads subject DN parameters from external config.
#   - Sets secure file permissions (600 for private keys, 644 for certificates).
#
# Dependencies:
#   - OpenSSL must be installed and available in the system PATH.
# -----------------------------------------------------------------------------

set -euo pipefail

# --- Paths ---
TLS_DIR="certs"
CA_KEY="$TLS_DIR/ca.key"
CA_CERT="$TLS_DIR/ca.crt"
CA_SERIAL="$TLS_DIR/ca.srl"

# Config directory relative to script location
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
CONFIG_DIR="$SCRIPT_DIR/config"
OPENSSL_EXT_CONF="$CONFIG_DIR/openssl.cnf"
SUBJECT_CONF="$CONFIG_DIR/subject.conf"

NGINX_KEY="$TLS_DIR/server.key"
NGINX_CERT="$TLS_DIR/server.crt"

POSTGRES_KEY="$TLS_DIR/postgres.key"
POSTGRES_CERT="$TLS_DIR/postgres.crt"

RABBITMQ_KEY="$TLS_DIR/rabbitmq.key"
RABBITMQ_CERT="$TLS_DIR/rabbitmq.crt"

REDIS_KEY="$TLS_DIR/redis.key"
REDIS_CERT="$TLS_DIR/redis.crt"
REDIS_DH="$TLS_DIR/redis.dh"

KRAKEND_KEY="$TLS_DIR/krakend.key"
KRAKEND_CERT="$TLS_DIR/krakend.crt"

# -----------------------------------------------------------------------------
# Function: usage
# Description:
#   Prints usage information for the script.
# -----------------------------------------------------------------------------
usage() {
    cat "$CONFIG_DIR/usage.txt"
}

# -----------------------------------------------------------------------------
# Load subject parameters from config
# -----------------------------------------------------------------------------
if [[ -f "$SUBJECT_CONF" ]]; then
    # shellcheck source=/dev/null
    source "$SUBJECT_CONF"
else
    echo "Error: Subject config file '$SUBJECT_CONF' not found."
    exit 1
fi

# -----------------------------------------------------------------------------
# Function: generate_key
# Description:
#   Generates an RSA private key with a specified bit length.
#
# Arguments:
#   $1 - Path to output private key file
#   $2 - (Optional) Number of bits for RSA key (default: 2048)
# -----------------------------------------------------------------------------
generate_key() {
    local keyfile=$1
    local bits=${2:-2048}

    if [ -d "$keyfile" ]; then
        echo "Warning: $keyfile is a directory, removing it."
        rm -rf "$keyfile"
    fi

    echo "Generating RSA private key: $keyfile ($bits bits)..."
    openssl genpkey -algorithm RSA -out "$keyfile" -pkeyopt rsa_keygen_bits:$bits
    chmod 600 "$keyfile"
}

# -----------------------------------------------------------------------------
# Function: generate_cert
# Description:
#   Generates an X.509 certificate signed by the root CA.
#
# Arguments:
#   $1 - Path to private key file
#   $2 - Path to output certificate file
#   $3 - Common Name (CN) for the certificate subject
#   $4 - (Optional) Additional OpenSSL extensions/options
# -----------------------------------------------------------------------------
generate_cert() {
    local keyfile=$1
    local certfile=$2
    local cn=$3
    local ext_opts=${4:-}

    echo "Generating certificate: $certfile with CN=$cn..."

    local subj="/C=$COUNTRY/ST=$STATE/L=$LOCALITY/O=$ORG/OU=$ORG_UNIT/CN=$cn/emailAddress=$EMAIL"

    openssl req -new -sha256 -key "$keyfile" -subj "$subj" | \
        openssl x509 -req -sha256 \
            -CA "$CA_CERT" \
            -CAkey "$CA_KEY" \
            -CAserial "$CA_SERIAL" \
            -CAcreateserial \
            -days 365 \
            $ext_opts \
            -out "$certfile"
    chmod 644 "$certfile"

    echo "Certificate $certfile created."
}

# -----------------------------------------------------------------------------
# Function: generate_ca
# Description:
#   Generates a root CA private key and self-signed certificate if they do not exist.
# -----------------------------------------------------------------------------
generate_ca() {
    mkdir -p "$TLS_DIR"

    if [[ ! -f "$CA_KEY" ]]; then
        echo "Generating root CA private key..."
        openssl genpkey -algorithm RSA -out "$CA_KEY" -pkeyopt rsa_keygen_bits:4096
        chmod 600 "$CA_KEY"
    else
        echo "Root CA private key already exists, skipping."
    fi

    if [[ ! -f "$CA_CERT" ]]; then
        echo "Generating root CA certificate..."
        local subj="/C=$COUNTRY/ST=$STATE/L=$LOCALITY/O=$ORG/OU=$ORG_UNIT/CN=localhost/emailAddress=$EMAIL"
        openssl req -x509 -new -nodes -sha256 \
            -key "$CA_KEY" \
            -days 3650 \
            -subj "$subj" \
            -out "$CA_CERT"
        chmod 644 "$CA_CERT"
    else
        echo "Root CA certificate already exists, skipping."
    fi
}

# -----------------------------------------------------------------------------
# Function: generate_dh_params
# Description:
#   Generates Diffie-Hellman parameters for secure key exchange.
# -----------------------------------------------------------------------------
generate_dh_params() {
    echo "Generating Diffie-Hellman parameters (this may take a few minutes)..."
    openssl dhparam -out "$REDIS_DH" 2048
    chmod 644 "$REDIS_DH"
    echo "DH parameters saved to $REDIS_DH"
}

# -----------------------------------------------------------------------------
# Function: main
# Description:
#   Main execution flow of the script.
# -----------------------------------------------------------------------------
main() {
    generate_ca

    if [[ ! -f "$OPENSSL_EXT_CONF" ]]; then
        echo "Error: OpenSSL extensions config '$OPENSSL_EXT_CONF' not found."
        exit 1
    fi

    # Generate nginx key and cert, named as server.key and server.crt
    generate_key "$NGINX_KEY"
    generate_cert "$NGINX_KEY" "$NGINX_CERT" "nginx" "-extfile $OPENSSL_EXT_CONF -extensions server_cert"

    # Generate client key and cert (optional)
    generate_key "$TLS_DIR/client.key"
    generate_cert "$TLS_DIR/client.key" "$TLS_DIR/client.crt" "client" "-extfile $OPENSSL_EXT_CONF -extensions client_cert"

    # Generate postgres key and cert
    generate_key "$POSTGRES_KEY"
    generate_cert "$POSTGRES_KEY" "$POSTGRES_CERT" "postgres" "-extfile $OPENSSL_EXT_CONF -extensions server_cert"

    # Generate rabbitmq key and cert
    generate_key "$RABBITMQ_KEY"
    chmod 644 "$RABBITMQ_KEY"
    generate_cert "$RABBITMQ_KEY" "$RABBITMQ_CERT" "rabbitmq" "-extfile $OPENSSL_EXT_CONF -extensions server_cert"
    chmod 644 "$RABBITMQ_CERT"

    # Generate redis key and cert
    generate_key "$REDIS_KEY"
    chmod 644 "$REDIS_KEY"
    generate_cert "$REDIS_KEY" "$REDIS_CERT" "redis" "-extfile $OPENSSL_EXT_CONF -extensions server_cert"
    chmod 644 "$REDIS_CERT"

    # Generate Krakend key and cert
    generate_key "$KRAKEND_KEY"
    generate_cert "$KRAKEND_KEY" "$KRAKEND_CERT" "krakend" "-extfile $OPENSSL_EXT_CONF -extensions server_cert"

    # Generate Diffie-Hellman parameters
    generate_dh_params

    echo "Certificate generation completed!"
}

# -----------------------------------------------------------------------------
# Script execution starts here
# -----------------------------------------------------------------------------

# Check if openssl is installed
if ! command -v openssl &>/dev/null; then
    echo "Error: openssl is not installed."
    exit 1
fi

# Show help if requested
if [[ "${1:-}" =~ ^(-h|--help)$ ]]; then
    usage
    exit 0
fi

main
