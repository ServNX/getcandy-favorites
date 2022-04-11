<?php

return [
    /*
     * If you have GetCandy Admin Hub installed set this to true (default is false).
     */
    'hub' => false,

    /*
     * User tables foreign key name.
     */
    'user_foreign_key' => 'user_id',

    /*
     * Table name for favorites records.
     * Note: We will automatically apply the table prefix configured for GetCandy
     */
    'favorites_table' => 'favorites',

    /*
     * Model name for favorite record.
     */
    'favorite_model' => Servnx\GetCandyFavorite\Favorite::class,
];
