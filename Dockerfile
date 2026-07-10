FROM ghcr.io/unb-libraries/drupal:11.x-1.x-unblib

# Install additional OS packages.
ENV ADDITIONAL_OS_PACKAGES="postfix php84-ldap php84-xmlreader php84-zip php84-pecl-redis"
ENV DRUPAL_SITE_ID="nbbib"
ENV DRUPAL_SITE_URI="nbbib.lib.unb.ca"
ENV DRUPAL_SITE_UUID="8c0eeb11-ba32-495f-9e90-4b4eafb796f8"

# Build application.
COPY ./build/ /build/
RUN ${RSYNC_MOVE} /build/scripts/container/ /scripts/ && \
  /scripts/addOsPackages.sh && \
  /scripts/initOpenLdap.sh && \
  /scripts/setupStandardConf.sh && \
  /scripts/build.sh

# Deploy configuration.
COPY ./configuration ${DRUPAL_CONFIGURATION_DIR}
RUN /scripts/pre-init.d/72_secure_config_sync_dir.sh

# Deploy custom modules, themes.
COPY ./custom/themes ${DRUPAL_ROOT}/themes/custom
COPY ./custom/modules ${DRUPAL_ROOT}/modules/custom

# Container metadata.
ARG BUILD_DATE
ARG VCS_REF
ARG VERSION
LABEL org.opencontainers.image.title="nbbib.lib.unb.ca" \
  org.opencontainers.image.description="nbbib.lib.unb.ca provides a searchable database of citations included in the over-arching New Brunswick Bibliography projects." \
  org.opencontainers.image.vendor="University of New Brunswick Libraries" \
  org.opencontainers.image.authors="UNB Libraries <libsupport@unb.ca>" \
  org.opencontainers.image.url="https://nbbib.lib.unb.ca" \
  org.opencontainers.image.source="https://github.com/unb-libraries/nbbib.lib.unb.ca" \
  org.opencontainers.image.version="$VERSION" \
  org.opencontainers.image.revision="$VCS_REF" \
  org.opencontainers.image.created="$BUILD_DATE" \
  ca.unb.lib.generator="drupal11"
