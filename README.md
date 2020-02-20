# ReactPHP tiny balancer

This is a ReactPHP based tiny load balanced for testing and development
purposes. With a simple configuration and an already published docker image, you
will be able to manage multiple servers in your docker-compose without any pain
by balancing your traffic among them all.

## Install

You can install the balancer by just cloning the repository somewhere in your
localhost.

```bash
cd /tmp/
git clone git@github.com:driftphp/tiny-load-balancer.git
cd tiny-load-balancer
```

Then, you should install the composer dependencies. Make sure you have composer
installed locally. If you have some php platform issues, just `composer update`
instead of install.

```bash
composer install
```

## Use it

You can use the balancer as a simple random balancer. Not even a round robin.
This repository is meant to be used as a small piece of code for balancing 
TCP requests among a set of ReactPHP servers, so a simple random balancing would
be enough for that purpose.

```bash
./balancer 8000 8001
./balancer 8000 myhost:8001 127.0.0.1:8002
./balancer 8000 myhost:8001-8010
./balancer 8000 :8001-8010
```

- By default, local `127.0.0.1` host will be used if only ports are defined
- Ports with format `8001-8010` will result in a sequence of all ports between
  both numbers, `8001, 8002, 8003... 8010`
- You can use named hosts
- You can add multiple hosts and ports

By default, debug will be enabled. You can disable output by using the flag
`silence`.

./balancer 8000 8001 --silence

## docker-compose

You can add this balancer in your docker-compose file.

```yaml
version: '3'
services:
  tiny_balancer:
    image: "driftphp/tiny-balancer"
    ports:
      - "8000:8000"
    networks: [main]
    entrypoint: [
      "/balancer",
      "8000",
      "server_1:8000",
      "server_2:8000"
    ]

  server_1:
    build: .
    networks: [main]
    container_name: server_1
    entrypoint: ["/server-entrypoint.sh"]

  server_2:
    build: .
    networks: [main]
    container_name: server_2
    entrypoint: ["/server-entrypoint.sh"]

networks:
  main:
```