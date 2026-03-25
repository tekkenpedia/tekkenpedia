Run `bin/ci/validate --theme=summary` and analyze the output.

Only fix errors reported by **phpcs** and **phpstan**. Ignore all other validation tools (composer-normalize, composer-require-checker, composer-validate, lint-yaml, phpdd, shellcheck, unused-scanner).

For each error:
1. Read the file mentioned in the error
2. Fix the issue
3. Re-run `bin/ci/validate --theme=summary` to confirm the fix

Repeat until phpcs and phpstan report no errors.
