<?php

/**
 * ArrayUtil
 * array-related utility functions
 */
class ArrayUtil {
        
    /**
     * move an array's element from one position to another 
     *
     * @param  mixed $a
     * @param  mixed $oldPosition
     * @param  mixed $newPosition
     */
    public static function move(&$a, $oldPosition, $newPosition) {
        if ($oldPosition==$newPosition) {return;}
        array_splice($a,max($newPosition,0),0,array_splice($a,max($oldPosition,0),1));
    }
}