#!/usr/bin/env sh

echo ''
echo '// Building test environment'

composer --quiet --no-interaction update --optimize-autoloader  > /dev/null

echo ''
echo ' [OK] Test environment built'
echo ''

vendor/bin/phpunit && \
    vendor/bin/phpspec --no-interaction run --format=dot && \
    vendor/bin/php-cs-fixer fix --dry-run src && \
    vendor/bin/php-cs-fixer fix --dry-run tests && \
    vendor/bin/php-cs-fixer fix --dry-run spec --fixers=-visibility
