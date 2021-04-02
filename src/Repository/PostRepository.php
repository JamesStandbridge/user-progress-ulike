<?php 

class PostRepository {

    /**
     * Count the number of review posted by an User by his ID
     *
     * @param integer $userID
     * @return integer
     */
    public function countReviewByUser(int $userID) : int
    {
        global $wpdb;

        $reviews = $wpdb->get_results("
            SELECT count(*) as count_review
            FROM {$wpdb->prefix}comments as C
            WHERE C.user_id = ".$userID." AND C.comment_type = 'review'
	    ");

        return $reviews[0]->count_review;
    }

    /**
     * Count the number of comment posted by an User by his ID
     *
     * @param integer $userID
     * @return integer
     */
    public function countCommentByUser(int $userID) : int
    {
        global $wpdb;

        $comments = $wpdb->get_results("
            SELECT count(*) as count_comment
            FROM {$wpdb->prefix}comments as C
            WHERE C.user_id = ".$userID." AND C.comment_type = 'comment'
        ");
    
        return $comments[0]->count_comment;
    }

    /**
     * Count the number of like for each comment posted by an User
     *
     * @param integer $userID
     * @return integer
     */    
    public function countLikedCommentByUser(int $userID) : int
    {
        global $wpdb;

        $commentLikes = $wpdb->get_results("
            SELECT count(*) as count_like
            FROM `{$wpdb->prefix}ulike_comments` as ULC
            JOIN `{$wpdb->prefix}comments` as WPC
            ON WPC.comment_ID = ULC.comment_id
            WHERE WPC.user_id = ".$userID." AND ULC.status = 'like'
	    ");

        return $commentLikes[0]->count_like;    
    }
}