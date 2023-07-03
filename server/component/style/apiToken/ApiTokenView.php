<?php
/* This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/. */
?>
<?php
require_once __DIR__ . "/../../../../../../component/BaseView.php";

/**
 * The view class of the asset select component.
 */
class ApiTokenView extends BaseView
{
    /* Constructors ***********************************************************/

    /**
     * The constructor.
     *
     * @param object $model
     *  The model instance of the component.
     */
    public function __construct($model, $controller)
    {
        parent::__construct($model, $controller);
    }

    /* Private Methods ********************************************************/

    /**
     * Output the api token
     */
    private function output_view_mode()
    {
        $this->output_alert();
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

        $card = new BaseStyleComponent(
            "card",
            array(
                "css" => "mb-3",
                "is_expanded" => true,
                "is_collapsible" => false,
                "title" => "API Token",
                "children" => array(
                    new BaseStyleComponent("descriptionItem", $field_api_token),
                    new BaseStyleComponent("descriptionItem", $field_created_at),
                    new BaseStyleComponent("descriptionItem", $field_updated_at),
                    $this->output_api_token_buttons()
                )
            )
        );
        $apiToken = new BaseStyleComponent("div", array(
            "css" => "container my-3",
            "children" => array($card)
        ));
        $apiToken->output_content();
    }

    /**
     * Output API token buttons
     */
    private function output_api_token_buttons()
    {
        $user = $this->model->get_api_user();
        $generate_token = new BaseStyleComponent("form", array(
            "label" => "Generate API token",
            "id" => "generate_token",
            "url" => '#',
            "confirmation_title" => 'API Token',
            "confirmation_cancel" => 'Cancel',
            "confirmation_continue" => 'Generate',
            "confirmation_message" => 'Do you want to generate a new API token?',
            "type" => "warning",
            "children" => array(
                new BaseStyleComponent("input", array(
                    "type_input" => "hidden",
                    "name" => "mode",
                    "is_required" => true,
                    "value" => API_GENERATE_TOKEN,
                )),
            )
        ));
        $revoke_token = new BaseStyleComponent("form", array(
            "label" => "Revoke API token",
            "url" => '#',
            "id" => "revoke_token",
            "type" => "danger",
            "css" => "ml-auto",
            "confirmation_title" => 'API Token',
            "confirmation_cancel" => 'Cancel',
            "confirmation_continue" => 'Revoke',
            "confirmation_message" => 'Do you want to revoke the API token?',
            "children" => array(
                new BaseStyleComponent("input", array(
                    "type_input" => "hidden",
                    "name" => "mode",
                    "is_required" => true,
                    "value" => API_REVOKE_TOKEN,
                )),
            )
        ));

        $api_btns_children = array($generate_token);
        if (isset($user['api_token']) && $user['api_token']) {
            // show btn revoke only if there is a api token
            $api_btns_children[] = $revoke_token;
        }
        $api_btns = new BaseStyleComponent("div", array(
            "css" => "d-flex",
            "children" => $api_btns_children
        ));
        return $api_btns;
    }

    /* Public Methods *********************************************************/

    /**
     * Render the footer view.
     */
    public function output_content()
    {
        $this->output_view_mode();
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
        $this->output_controller_alerts_fail(true);
        $this->output_controller_alerts_success(true);
    }
}
?>
