# Paperless

Index and archive all of your scanned paper documents.

## Installation

This repository is only for the Unraid implementation of Paperless. For full
instructions to deploy it on other systems please see
[The Paperless Project](https://github.com/the-paperless-project/paperless)
documentation.

## Usage

Paperless does not control your scanner, it only helps you deal with what your
scanner produces

1. Buy a document scanner that can write to a place on your network. If you need
some inspiration, have a look at the
[scanner recommendations](https://paperless.readthedocs.io/en/latest/scanners.html)
page.

2. Set it up to "scan to FTP" or something similar. It should be able to push
scanned images to your server without you having to do anything. Of course if
your scanner doesn't know how to automatically upload the file somewhere, you
can always do that manually. Paperless doesn't care how the documents get into
its consumption directory.

3. Have the target server run the Paperless consumption script to OCR the file
and index it into a local database.

4. Use the web frontend to sift through the database and find what you want.

5. Download the PDF you need/want via the web interface and do whatever you like
with it. You can even print it and send it as if it's the original. In most
cases, no one will care or notice.

## Contributing

Pull requests are welcome. For major changes, please open an issue first to
discuss what you would like to change.

## License
[GNU GPLv3](https://choosealicense.com/licenses/gpl-3.0/)
