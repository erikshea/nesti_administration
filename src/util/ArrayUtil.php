<?php

class ArrayUtil {
    public static function move(&$a, $oldPosition, $newPosition) {
        if ($oldPosition==$newPosition) {return;}
        array_splice($a,max($newPosition,0),0,array_splice($a,max($oldPosition,0),1));
    }
}