INSTRUCTIONS FOR DISTRIBUTION SYSTEM:

1. For the client, all you need is a distribution.sh and distribution.conf
2. Put those two files in whatever directory you want.
3. Set up distribution.conf, make sure the paths are correct. Absolute-path is the path to the SERVER's folder.
4. Optional: add an exclude file, which will exclude directories/paths from the upload.
------------------------------------------------------------------------------
1. For the server you need distribution.sh, distribution.conf, and distribution_client_dl.sh
2. distribution_client_dl.sh is what allows the client to pull files from the server.
3. Setup is almost the same as the client, but server-ip, server-name, and absolute-path are not necessary.
