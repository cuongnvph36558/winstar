<?php

namespace App\Helpers;

class StorageHelper
{
    /**
     * Format storage capacity for display
     * 
     * @param string|null $capacity
     * @return string
     */
    public static function formatCapacity($capacity)
    {
        if (empty($capacity)) {
            return '-';
        }

        // Remove any existing GB/TB suffix and normalize
        $capacity = trim($capacity);
        
        // If already contains GB or TB, return as is
        if (preg_match('/\d+\s*(GB|TB)$/i', $capacity)) {
            return strtoupper($capacity);
        }
        
        // If it's just a number, assume it's GB
        if (is_numeric($capacity)) {
            return $capacity . 'GB';
        }
        
        // If it contains GB or TB in the middle, extract and format
        if (preg_match('/(\d+)\s*(GB|TB)/i', $capacity, $matches)) {
            return $matches[1] . strtoupper($matches[2]);
        }
        
        // Return as is if no pattern matches
        return $capacity;
    }

    /**
     * Get storage capacity without unit for comparison
     * 
     * @param string|null $capacity
     * @return int|null
     */
    public static function getCapacityValue($capacity)
    {
        if (empty($capacity)) {
            return null;
        }

        $capacity = trim($capacity);
        
        // Extract numeric value
        if (preg_match('/(\d+)/', $capacity, $matches)) {
            $value = (int) $matches[1];
            
            // Convert TB to GB for comparison
            if (preg_match('/TB/i', $capacity)) {
                return $value * 1024;
            }
            
            return $value;
        }
        
        return null;
    }

    /**
     * Get storage unit (GB or TB)
     * 
     * @param string|null $capacity
     * @return string
     */
    public static function getCapacityUnit($capacity)
    {
        if (empty($capacity)) {
            return 'GB';
        }

        $capacity = trim($capacity);
        
        if (preg_match('/TB/i', $capacity)) {
            return 'TB';
        }
        
        return 'GB';
    }
}
