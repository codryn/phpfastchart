FROM mcr.microsoft.com/devcontainers/php:1-8.3-bullseye

# Install GD extension dependencies and build GD with WebP support
RUN apt-get update && \
    apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev libwebp-dev && \
    docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp && \
    docker-php-ext-install -j$(nproc) gd && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*
