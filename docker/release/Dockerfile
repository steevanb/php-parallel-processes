FROM ubuntu:20.04

RUN \
    apt-get update \
    && apt-get install -y \
        bash \
        cloc \
        jq \

    # Purge
    && apt-get autoremove -y \
    && apt-get clean \
    && apt-get autoclean \
    && rm -rf /tmp/*
