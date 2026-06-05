#!/bin/sh
# Smoke test script for Book Review PHP
# Run inside the nginx or app container: sh scripts/smoke.sh
# Or from host: docker compose exec nginx sh scripts/smoke.sh

set -e

BASE="${BASE_URL:-http://localhost}"
PASS=0
FAIL=0
LOG_FILE="/var/www/html/storage/logs/app.log"

echo "========================================"
echo "Book Review — Smoke Test"
echo "Base URL: $BASE"
echo "========================================"

# Login helper
login() {
    TOKEN=$(curl -s -c /tmp/smoke_cookie.txt "$BASE/login" \
        | grep -o '_token[^"]*" value="[^"]*"' | head -1 \
        | grep -o 'value="[^"]*"' | cut -d'"' -f2)
    curl -s -o /dev/null -b /tmp/smoke_cookie.txt -c /tmp/smoke_cookie.txt \
        --data-urlencode "_token=$TOKEN" \
        --data-urlencode "email=${1:-admin@example.com}" \
        --data-urlencode "password=${2:-password}" \
        "$BASE/login"
}

check() {
    local label="$1" url="$2" expected="${3:-200}" use_auth="${4:-yes}"
    if [ "$use_auth" = "yes" ]; then
        code=$(curl -s -o /dev/null -w '%{http_code}' -b /tmp/smoke_cookie.txt "$BASE$url")
    else
        code=$(curl -s -o /dev/null -w '%{http_code}' "$BASE$url")
    fi
    if [ "$code" = "$expected" ]; then
        echo "  PASS  $label → $code"
        PASS=$((PASS + 1))
    else
        echo "  FAIL  $label → $code (expected $expected)"
        FAIL=$((FAIL + 1))
    fi
}

echo ""
echo "--- Static Assets ---"
check "ui.css"        "/css/ui.css"      200 "no"
check "favicon.svg"   "/favicon.svg"     200 "no"

echo ""
echo "--- Public Routes ---"
check "home"          "/"                200 "no"
check "books list"    "/books"           200 "no"
check "book detail"   "/books/2"         200 "no"
check "book search"   "/books/search"    200 "no"
check "login page"    "/login"           200 "no"
check "register page" "/register"        200 "no"
check "SSE stream"    "/events/latest-reviews" 200 "no"

echo ""
echo "--- Authenticated Routes ---"
login "admin@example.com" "password"
check "dashboard"     "/dashboard"       200
check "favorites"     "/favorites"       200
check "profile"       "/profile"         200

echo ""
echo "--- Admin Routes ---"
check "admin panel"   "/admin"           200
check "admin books"   "/admin/books"     200
check "books create"  "/admin/books/create" 200
check "books edit"    "/admin/books/1/edit" 200
check "categories"    "/admin/categories"    200
check "cat create"    "/admin/categories/create" 200
check "cat edit"      "/admin/categories/1/edit" 200
check "admin reviews" "/admin/reviews"   200
check "admin users"   "/admin/users"     200
check "user edit"     "/admin/users/2/edit" 200

echo ""
echo "--- CSRF Protection ---"
code=$(curl -s -o /dev/null -w '%{http_code}' \
    --data-urlencode "email=test@test.com" \
    --data-urlencode "password=wrong" \
    "$BASE/login")
if [ "$code" = "419" ]; then
    echo "  PASS  CSRF block → $code"
    PASS=$((PASS + 1))
else
    echo "  FAIL  CSRF block → $code (expected 419)"
    FAIL=$((FAIL + 1))
fi

echo ""
echo "--- Error Log ---"
if [ -f "$LOG_FILE" ] && [ -s "$LOG_FILE" ]; then
    echo "  WARN  Log has content:"
    tail -5 "$LOG_FILE"
else
    echo "  PASS  Log clean"
    PASS=$((PASS + 1))
fi

echo ""
echo "========================================"
echo "Results: $PASS passed, $FAIL failed"
echo "========================================"

[ "$FAIL" -eq 0 ] && exit 0 || exit 1
