#!/usr/bin/env bash

ENV_ARGS=()
while IFS='=' read -r name value; do
    ENV_ARGS+=("-e" "$name=$value")
done < <(env | grep '^PP_')

readonly SOURCE_CODE_PATH=/composer/vendor/steevanb/php-parallel-processes

LOCAL_SOURCE=true
VOLUME_ARGS=()
if [[ "${LOCAL_SOURCE,,}" == "true" ]]; then
    VOLUME_ARGS+=("-v" "$(pwd):${SOURCE_CODE_PATH}")
fi

readonly EXAMPLE="${1}"
shift

docker \
    run \
        --rm \
        -it \
        "${ENV_ARGS[@]}" \
        "${VOLUME_ARGS[@]}" \
        steevanb/php-parallel-processes:1.1.0-alpine \
            php "${SOURCE_CODE_PATH}"/examples/"${EXAMPLE}" "$@"
