<p align="center"><img src="https://raw.githubusercontent.com/Indemnity83/paperless/master/public/img/app-icon.svg" height="100">

<p align="center">
<img alt="GitHub branch checks state" src="https://img.shields.io/github/checks-status/indemnity83/paperless/master?style=flat-square">
<a href="https://hub.docker.com/r/indemnity83/paperless"><img alt="Docker Pulls" src="https://img.shields.io/docker/pulls/indemnity83/paperless?style=flat-square"></a>
<a href="https://github.com/Indemnity83/paperless/blob/master/LICENSE"><img alt="GitHub" src="https://img.shields.io/github/license/indemnity83/paperless?style=flat-square"></a>
</p>

# About Paperless

Paperless is a dockerized Laravel application running intended to allow you to scan, index, and archive all of your paper documents digitally.

In order to keep the application simple to get up and running, the docker container runs everything necessary including the web server, queue server, search indexer and worker processes. 

**Note that this application is still in beta, and subject to breaking changes. Proceed with caution before you go burning all your paper documents!**

## Quick Start

**NOTE**: The Docker command provided in this quick start is given as an example
and parameters should be adjusted to your need.

Launch the Paperless docker container with the following command:
```
docker run -d \
    --name=paperless \
    -p 8000:8000 \
    -v /config:/app/storage/config:rw \
    -v /consume:/app/storage/consume:rw \
    -v /app:/app/storage/app:rw \
    indemnity83/paperless
```

Browse to `http://your-host-ip:8000` to access the Paperless web interface.

### Data Volumes

The following table describes data volumes used by the container.  The mappings
are set via the `-v` parameter.  Each mapping is specified with the following
format: `<HOST_DIR>:<CONTAINER_DIR>[:PERMISSIONS]`.

| Container path  | Permissions | Description |
|-----------------|-------------|-------------|
|`/app/storage/config`| rw | This is where the application stores its databases, indexes and any files needing persistency. |
|`/app/storage/consume`| rw | This folder is watched for PDF files which will be consumed into the app. |
|`/app/storage/app`| rw | This folder contains all the original PDF files and thumbnails. |

### Ports

Here is the list of ports used by the container.  They can be mapped to the host
via the `-p` parameter (one per port mapping).  Each mapping is defined in the
following format: `<HOST_PORT>:<CONTAINER_PORT>`.  The port number inside the
container cannot be changed, but you are free to use any port on the host side.

| Port | Mapping to host | Description |
|------|-----------------|-------------|
| 8000 | Mandatory | Port used to access the web interface of the application. |

### Environment Variables

To customize some properties of the container, the following environment
variables can be passed via the `-e` parameter (one for each variable).  Value
of this parameter has the format `<VARIABLE_NAME>=<VALUE>`.

| Variable       | Description                                  | Default |
|----------------|----------------------------------------------|---------|
|`UID`| ID of the user the application runs as.  See [User/Group IDs](#usergroup-ids) to better understand when this should be set. | `99` |
|`GID`| ID of the group the application runs as.  See [User/Group IDs](#usergroup-ids) to better understand when this should be set. | `100` |
|`TZ`| [TimeZone] of the container.  Timezone can also be set by mapping `/etc/localtime` between the host and the container. | `Etc/UTC` |
