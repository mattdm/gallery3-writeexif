gallery3-writeexif
==================

A simple module for Gallery 3 which uses exiftool to write changed metadata into JPG files.

There are a number of huge caveats:

1. It's not particularly well tested. It may eat all of your files.
2. Requires [exiftool][], and furthermore is hardcoded to look for 
   `/usr/bin/exiftool` -- that should be an option.
3. Only handles `Title` and `Description`.
4. A little slow.

On the plus side:

1. It seems to work for me.
2. Tries to avoid changing things which don't need changing.
3. It does rudimentary error checking and should at least _log_ if it's 
   broken something.



  [exiftool]: http://www.sno.phy.queensu.ca/~phil/exiftool/