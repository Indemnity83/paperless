# Paperless

<p align="left">
<img alt="GitHub branch checks state" src="https://img.shields.io/github/checks-status/indemnity83/paperless/master?style=flat-square">
<a href="https://hub.docker.com/r/indemnity83/paperless"><img alt="Docker Pulls" src="https://img.shields.io/docker/pulls/indemnity83/paperless?style=flat-square"></a>
<a href="https://github.com/Indemnity83/paperless/blob/master/LICENSE"><img alt="GitHub" src="https://img.shields.io/github/license/indemnity83/paperless?style=flat-square"></a>
</p>

<img align="left" src="https://raw.githubusercontent.com/Indemnity83/paperless/master/public/img/app-icon.svg" height="100">

Paperless is a dockerized Laravel application intended to allow you to scan, index, and archive all of your paper documents digitally.

The docker container contains everything necessary to keep the application simple, including the webserver, database, process queue, search index, and worker processes. If you don't know what any of that means, then don't worry about it. Just know that Paperless has your back!

```diff
@@ Note that this application is still in beta! @@
 
! There may be breaking changes. 
! Always backup your documents.
```

## Quick Start

**NOTE**: The Docker command provided in this quick start is an example
and you should adjust parameters to your need.

Launch the Paperless docker container with the following command:
```
docker run -d \
    --name=paperless \
    -p 8000:8000 \
    -v /config:/app/storage/config:rw \
    -v /consume:/app/storage/consume:rw \
    -v /data:/app/storage/data:rw \
    indemnity83/paperless
```

Browse to `http://your-host-ip:8000` to access the Paperless web interface.

### Data Volumes

The following table describes data volumes used by the container.  You can set the mappings via the `-v` parameter.  Each mapping follows the following
format: `<HOST_DIR>:<CONTAINER_DIR>[:PERMISSIONS]`.

| Container path          | Permissions | Description                                                                                     |
|-------------------------|-------------|-------------------------------------------------------------------------------------------------|
| `/app/storage/config`   | rw          | This is where the application stores its databases, indexes, and any files needing persistence. |
| `/app/storage/consume`  | rw          | Paperless will monitor this folder for PDF files to ingest into the app.                        |
| `/app/storage/data`     | rw          | This folder contains all the indexed PDF files and thumbnails.                                  |

### Ports

Here is the list of ports used by the container.  You can map them to the host
via the `-p` parameter (one per port mapping).  Each mapping follows the
following format: `<HOST_PORT>:<CONTAINER_PORT>`. You cannot change the port number inside the
container, but you can use any port on the host side.

| Port | Mapping to host | Description                                               |
|------|-----------------|-----------------------------------------------------------|
| 8000 | Mandatory       | Port used to access the web interface of the application. |

### Environment Variables

The following environment variables can be passed via the `-e` parameter (one for each variable) to customize the container's properties.  The value
of this parameter has the format `<VARIABLE_NAME>=<VALUE>`.

| Variable | Description                                  | Default |
|----------|----------------------------------------------|---------|
|`UID`     | ID of the user the application runs as.      | `99`    |
|`GID`     | ID of the group the application runs as.     | `100`   |
|`TZ`      | [TimeZone] of the container.                 | `UTC`   |

[TimeZone]: https://www.php.net/manual/en/timezones.php
