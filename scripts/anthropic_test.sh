#!/bin/sh

echo "Key prefix: $(echo "$ANTHROPIC_API_KEY" | cut -c1-6)..."

cat > /tmp/payload.json <<'JSON'
{"model":"claude-haiku-4-5","messages":[{"role":"user","content":"Prueba desde script"}]}
JSON

curl -i -s -X POST 'https://api.anthropic.com/v1/messages' -H "x-api-key: $ANTHROPIC_API_KEY" -H 'Content-Type: application/json' -d @/tmp/payload.json

rm /tmp/payload.json
