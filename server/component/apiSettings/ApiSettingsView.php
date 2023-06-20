<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../component/BaseView.php";

/**
 * The view class of the asset select component.
 */
class ApiSettingsView extends BaseView
{
    /* Constructors ***********************************************************/

    /**
     * The component params
     */
    private $params;

    /**
     * The constructor.
     *
     * @param object $model
     *  The model instance of the component.
     */
    public function __construct($model, $controller, $params)
    {
        $this->params = $params;
        parent::__construct($model, $controller);
    }

    /* Private Methods ********************************************************/

    /**
     * Output the api settings
     * @param boolean $show_edit_btn
     * If true the edit url is shown in the card
     */
    private function output_view_mode($show_edit_btn = true)
    {
        if (!$this->model->has_access_select()) {
            // if the user has no access to api do not show
            return;
        }
        $user = $this->model->get_api_user();
        $field_api_token = array(
            "title" => "API token",
            "help" => 'The API token that can be used for the Selfhelp API',
            "display" => 0,
            "css" => 'border-0',
            "children" => array(new BaseStyleComponent("rawText", array(
                "text" => (isset($user['api_token']) ? $user['api_token'] : '')
            )))
        );
        $field_created_at = array(
            "title" => "Created at",
            "help" => 'The date when the first API token was created',
            "display" => 0,
            "css" => 'border-0',
            "children" => array(new BaseStyleComponent("rawText", array(
                "text" => (isset($user['created_at']) ? $user['created_at'] : '')
            )))
        );
        $field_updated_at = array(
            "title" => "Updated at",
            "help" => 'The date when the current token was updated',
            "display" => 0,
            "css" => 'border-0',
            "children" => array(new BaseStyleComponent("rawText", array(
                "text" => (isset($user['updated_at']) ? $user['updated_at'] : '')
            )))
        );

        $card = new BaseStyleComponent("card", array(
            "css" => "mb-3",
            "is_expanded" => true,
            "is_collapsible" => false,
            "title" => "API Settings",
            "url_edit" => $show_edit_btn ? $this->model->get_services()->get_router()->get_link_url(PAGE_API_SETTINGS, array("mode" => SELECT)) : "",
            "children" => array(
                new BaseStyleComponent("form", array(
                    "label" => "",
                    "url" => '#',
                    "type" => "info",
                    "children" => array(
                        new BaseStyleComponent("descriptionItem", $field_api_token),
                        new BaseStyleComponent("descriptionItem", $field_created_at),
                        new BaseStyleComponent("descriptionItem", $field_updated_at)
                    )
                )),
            )
        ));
        $apiSettings = new BaseStyleComponent("div", array(
            "css" => "container my-3",
            "children" => array($card)
        ));
        $apiSettings->output_content();
    }

    /**
     * Output API settings buttons
     */
    private function output_api_settings_buttons()
    {
        $user = $this->model->get_api_user();
        $btn_back = new BaseStyleComponent("button", array(
            "label" => "Back to Profile",
            "url" => $this->model->get_link_url("profile"),
            "type" => "secondary",
        ));
        $btn_generate = new BaseStyleComponent("button", array(
            "label" => "Generate API token",
            "css" => "ml-3",
            "url" => $this->model->get_services()->get_router()->get_link_url(PAGE_API_SETTINGS, array("mode" => UPDATE)),
            "type" => "warning",
        ));
        $btn_revoke = new BaseStyleComponent("button", array(
            "label" => "Revoke API token",
            "url" => $this->model->get_services()->get_router()->get_link_url(PAGE_API_SETTINGS, array("mode" => DELETE)),
            "type" => "danger",
            "css" => "ml-auto",
        ));
        $api_btns_children = array($btn_back);
        if ($this->model->has_access_update()) {
            // show btn revoke only if there is a api token
            $api_btns_children[] = $btn_generate;
        }
        if (isset($user['api_token']) && $user['api_token'] && $this->model->has_access_delete()) {
            // show btn revoke only if there is a api token
            $api_btns_children[] = $btn_revoke;
        }
        $api_btns = new BaseStyleComponent("div", array(
            "css" => "container my-3 d-flex",
            "children" => $api_btns_children
        ));
        $api_btns->output_content();
    }

    /* Public Methods *********************************************************/

    /**
     * Render the footer view.
     */
    public function output_content()
    {
        if (isset($this->params['mode']) && in_array($this->params['mode'], array(UPDATE, SELECT, DELETE))) {
            require __DIR__ . "/tpl_api_settings.php";
        } else {
            $this->output_view_mode();
        }
    }

    public function output_content_mobile()
    {
        echo 'mobile';
    }

    /**
     * Render the alert message.
     */
    protected function output_alert()
    {
        $this->output_controller_alerts_fail();
        $this->output_controller_alerts_success();
    }
}
?>
