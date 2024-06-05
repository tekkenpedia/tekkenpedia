#!/usr/bin/env bash

set -eu

source "${ROOT_PATH}"/bin/docker/docker-interactive-parameter.inc.bash
source "${ROOT_PATH}"/bin/docker/uid-gid.inc.bash
source "${ROOT_PATH}"/bin/docker.inc.bash

if [ -z "${I_AM_DOCKER_CONTAINER:-}" ]; then
    docker \
        run \
            ${DOCKER_INTERACTIVE_PARAMETER} \
            ${DOCKER_TTY_PARAMETER} \
            --rm \
            --mount type=bind,source="${ROOT_PATH}",target=/app \
            --user "${DOCKER_UID}":"${DOCKER_GID}" \
            --name tekkenpedia_ci \
            "${DOCKER_PHP_DEV_IMAGE_NAME}" \
            /app/bin/dev/"$(basename "${0}")" \
            ${@:-}

    exit 0
fi
