#!/usr/bin/env bash

# Parfois le terminal n'est pas "interactive", par exemple dans les CI

set -eu

set +e
tty -s > /dev/null 2>&1 && isInteractiveShell=true || isInteractiveShell=false
set -e

if ${isInteractiveShell}; then
    readonly DOCKER_INTERACTIVE_PARAMETER="--interactive"
    readonly DOCKER_TTY_PARAMETER="--tty"
else
    readonly DOCKER_INTERACTIVE_PARAMETER=
    readonly DOCKER_TTY_PARAMETER=
fi
