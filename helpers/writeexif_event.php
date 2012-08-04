<?php defined('SYSPATH') or die('No direct script access.');
/*
 * Write Exif
 * A simple module for Gallery 3 which uses exiftool to write changed metadata into JPG files. 
 *
 * Copyright (C) 2012 Matthew Miller (Your contributions welcome!)
 * 
 * This program is free software; you can redistribute it and/or modify 
 * it under the terms of the GNU General Public License as published by 
 * the Free Software Foundation; either version 2 of the License, or (at 
 * your option) any later version. 
 * 
 * This program is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU 
 * General Public License for more details. 
 * 
 * You should have received a copy of the GNU General Public License 
 * along with this program; if not, write to the Free Software 
 * Foundation, Inc., 51 Franklin Street - Fifth Floor, Boston, MA  02110-1301, USA. 
*/ 

class writeexif_event_Core {
  static function item_updated($original, $new) {

    if ( ! $new->is_photo() )  {
          return;
    }
    
    $name = $new->name;
    $path = $new->file_path();
        
    $olddesc= $original->description;
    $newdesc= $new->description;
    if ( $olddesc != $newdesc ) {
        unset($exiftooloutput);
        /* fixme: don't hard-code location -- make it selectable */
        /* for a future version, use an all-php exif library. */
        exec("/usr/bin/exiftool -ignoreMinorErrors -binary -Description " . escapeshellarg($path), &$exiftooloutput );
        $exifdesc = $exiftooloutput[0];
        if ( $newdesc != $exifdesc ) {
            /* fixme: allow option for -overwrite_original_in_place */
            exec("/usr/bin/exiftool -ignoreMinorErrors -overwrite_original -quiet -binary -Description=" .escapeshellarg($newdesc) . " " . escapeshellarg($path), &$exiftooloutput, &$rc );
            if ($rc == 0) {
                log::info("writeexif","Changed $name exif description from \"$exifdesc\" to \"$newdesc\"");
            } else {
                log::error("writeexif","Failed to change $name description from \"$exifdesc\" to \"$newdesc\"");
            }
        }
    }

    /* fixme -- use a function rather than copy-paste! */
    $oldtitle= $original->title;
    $newtitle= $new->title;
    if ( $oldtitle != $newtitle ) {
        unset($exiftooloutput);
        exec("/usr/bin/exiftool -ignoreMinorErrors -binary -Title " . escapeshellarg($path), &$exiftooloutput );
        $exiftitle = $exiftooloutput[0];
        if ( $newtitle != $exiftitle ) {
            exec("/usr/bin/exiftool -ignoreMinorErrors -overwrite_original -quiet -binary -Title=" .escapeshellarg($newtitle) . " " . escapeshellarg($path), &$exiftooloutput, &$rc );
            if ($rc == 0) {
                log::info("writeexif","Changed $name exif title from \"$exiftitle\" to \"$newtitle\"");
            } else {
                log::error("writeexif","Failed to change $name title from \"$exiftitle\" to \"$newtitle\"");
            }
        }
    }


  }
}
        