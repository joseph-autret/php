<?php

require_once 'App/models/ActivityModel.php';
class AdminPanelController{

    public function display_admin_panel() {
        $activityModel = new ActivityModel();
        $activities = $activityModel->getAllActivities();
        include 'App/views/adminPanel.php';
    }
}
