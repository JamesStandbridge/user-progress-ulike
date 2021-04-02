<?php 

define('LIKE_WEIGHT', 2);
define('COMMENT_WEIGHT', 1);
define('REVIEW_WEIGHT', 2);

require_once plugin_dir_path(__FILE__) . "/Repository/UserRepository.php";
require_once plugin_dir_path(__FILE__) . "/Repository/PostRepository.php";
require_once plugin_dir_path(__FILE__) . "/Entity/User.php";

class ProgressionManager {
    private $userRepository;
    private $postRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->postRepository = new PostRepository();
    }

    /**
     * Main function : Update the progressions of all users in WebSite Database
     *
     * @return void
     */
    public function updateProgressions() : void
    {
        $userIDs = $this->userRepository->getUserIDs();
        $users = $this->hydrateUsers($userIDs);

        foreach($users as $user) {
            $this->userRepository->updateUserProgress($user->getID(), $user->getCurrentProgression());
        }
    }

    /**
     * Create an Array of user with an Array of IDs
     * Hydrate the current progression of each user
     *
     * @param  array $userIDs
     * @return User[] 
     */
    private function hydrateUsers(array $userIDs) 
    {
        $users = [];
       
        foreach($userIDs as $userData) {
            $user = new User();
            $user->setID($userData->ID);
            $user->setCurrentProgression($this->calculateCurrentProgressionByUser($userData->ID));
            $users[] = $user;
        }

        return $users;
    }

    /**
     * Calculate the current progression of a User by his ID
     *
     * @param integer $userID
     * @return integer
     */
    private function calculateCurrentProgressionByUser(int $userID)
    {
        $count_reviews = $this->postRepository->countReviewByUser($userID);
        $count_comments = $this->postRepository->countCommentByUser($userID);
        $count_comments_liked = $this->postRepository->countLikedCommentByUser($userID);

        return $count_reviews * REVIEW_WEIGHT + $count_comments * COMMENT_WEIGHT + $count_comments_liked * LIKE_WEIGHT;
    }
}