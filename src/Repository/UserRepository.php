<?php 

class UserRepository {

    /**
     * Get all User IDs in WebSite Database
     *
     * @return integer[]
     */
    public function getUserIDs() : array
    {
        global $wpdb;

        $userIDs = $wpdb->get_results("
            SELECT U.ID
            FROM {$wpdb->prefix}users as U
        ");

        return $userIDs;
    }

    /**
     * Update the progression of an user by his ID
     *
     * @param integer $userID
     * @param integer $points
     * @return void
     */
    public function updateUserProgress(int $userID, int $points) : void
    {
        global $wpdb;

        $wpdb->query("
            UPDATE {$wpdb->prefix}users SET 
            user_progress_points = $points
            WHERE ID = '$userID'
        ");
    }
}