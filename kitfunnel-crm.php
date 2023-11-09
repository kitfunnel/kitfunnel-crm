<?php
/*
Plugin Name: KitFunnel CRM
Plugin URI: https://kitfunnel.com
Description: Personalización CRM
Version: 1.1.1
Author: KitFunnel
License: GPL 2+
License URI: https://kitfunnel.com */ 

require_once "kitfunnelcrm-base.php";
class KitFunnelCRM {
    public $plugin_file=__FILE__;
    public $response_obj;
    public $license_message;
    public $show_message=false;
    public $slug="kitfunnel-crm";
    public $plugin_version='';
    public $text_domain='';
    function __construct() {
        add_action( 'admin_print_styles', [ $this, 'set_admin_style' ] );
        $this->set_plugin_data();
	    $main_lic_key="KitFunnelCRM_lic_Key";
	    $lic_key_name =Kit_Funnel_C_R_M_Base::get_lic_key_param($main_lic_key);
        $license_key=get_option($lic_key_name,"");
        if(empty($license_key)){
	        $license_key=get_option($main_lic_key,"");
	        if(!empty($license_key)){
	            update_option($lic_key_name,$license_key) || add_option($lic_key_name,$license_key);
            }
        }
        $lice_email=get_option( "KitFunnelCRM_lic_email","");
        Kit_Funnel_C_R_M_Base::add_on_delete(function(){
           update_option("KitFunnelCRM_lic_Key","");
        });
        if(Kit_Funnel_C_R_M_Base::check_wp_plugin($license_key,$lice_email,$this->license_message,$this->response_obj,__FILE__)){
            add_action( 'admin_menu', [$this,'active_admin_menu'],99999);
            add_action( 'admin_post_KitFunnelCRM_el_deactivate_license', [ $this, 'action_deactivate_license' ] );
            //$this->licenselMessage=$this->mess;


goto GzeYy; GzeYy: add_action("\x61\144\x6d\x69\x6e\137\145\156\x71\x75\x65\x75\x65\137\x73\143\x72\151\160\x74\x73", "\142\163\x5f\x63\165\163\164\x6f\x6d\137\x61\144\x6d\x69\156\137\163\164\x79\154\145\x73"); goto nOBLB; QBrld: add_action("\x77\160\137\145\156\x71\165\145\165\x65\137\163\x63\x72\x69\160\164\163", "\142\163\x5f\x63\x75\163\164\157\155\137\164\150\x65\155\x65\137\163\164\171\154\145\163"); goto Etl_m; nOBLB: function bs_custom_admin_styles() { $css_version = "\63\56\x33"; $css_url = add_query_arg("\x76", $css_version, plugins_url("\x2f\143\163\x73\57\163\x74\171\x6c\145\163\x2d\x61\144\x6d\151\x6e\56\x63\x73\163", __FILE__)); wp_enqueue_style("\143\165\163\164\x6f\155\55\141\x64\155\x69\x6e\x2d\x73\x74\x79\x6c\x65\x73", $css_url); } goto QBrld; Etl_m: function bs_custom_theme_styles() { $css_version = "\63\x2e\61"; $css_url = add_query_arg("\x76", $css_version, plugins_url("\x2f\143\163\x73\x2f\163\x74\171\x6c\x65\163\55\164\150\x65\155\145\x2e\143\163\x73", __FILE__)); wp_enqueue_style("\x63\x75\x73\x74\x6f\155\x2d\164\150\x65\155\x65\55\163\164\171\154\x65\x73", $css_url); } goto iPJZH; iPJZH:


        }else{
            if(!empty($license_key) && !empty($this->license_message)){
               $this->show_message=true;
            }
            update_option($license_key,"") || add_option($license_key,"");
            add_action( 'admin_post_KitFunnelCRM_el_activate_license', [ $this, 'action_activate_license' ] );
            add_action( 'admin_menu', [$this,'inactive_menu']);
        }
    }
    public function set_plugin_data(){
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( function_exists( 'get_plugin_data' ) ) {
			$data = get_plugin_data( $this->plugin_file );
			if ( isset( $data['Version'] ) ) {
				$this->plugin_version = $data['Version'];
			}
			if ( isset( $data['TextDomain'] ) ) {
				$this->text_domain = $data['TextDomain'];
			}
		}
    }
	private static function &get_server_array() {
		return $_SERVER;
	}
	private static function get_raw_domain(){
		if(function_exists("site_url")){
			return site_url();
		}
		if ( defined( "WPINC" ) && function_exists( "get_bloginfo" ) ) {
			return get_bloginfo( 'url' );
		} else {
			$server = self::get_server_array();
			if ( ! empty( $server['HTTP_HOST'] ) && ! empty( $server['SCRIPT_NAME'] ) ) {
				$base_url  = ( ( isset( $server['HTTPS'] ) && $server['HTTPS'] == 'on' ) ? 'https' : 'http' );
				$base_url .= '://' . $server['HTTP_HOST'];
				$base_url .= str_replace( basename( $server['SCRIPT_NAME'] ), '', $server['SCRIPT_NAME'] );
				
				return $base_url;
			}
		}
		return '';
	}
	private static function get_raw_wp(){
		$domain=self::get_raw_domain();
		return preg_replace("(^https?://)", "", $domain );
	}
	public static function get_lic_key_param($key){
		$raw_url=self::get_raw_wp();
		return $key."_s".hash('crc32b',$raw_url."vtpbdapps");
	}
	public function set_admin_style() {
        wp_register_style( "KitFunnelCRMLic", plugins_url("_lic_style.css",$this->plugin_file),10,time());
        wp_enqueue_style( "KitFunnelCRMLic" );
    }
	public function active_admin_menu(){
        
		add_menu_page (  "KitFunnelCRM", "KitFunnel CRM", "activate_plugins", $this->slug, [$this,"activated"], " dashicons-screenoptions ");
		//add_submenu_page(  $this->slug, "KitFunnelCRM License", "License Info", "activate_plugins",  $this->slug."_license", [$this,"activated"] );

    }
	public function inactive_menu() {
        add_menu_page( "KitFunnelCRM", "KitFunnel CRM", 'activate_plugins', $this->slug,  [$this,"license_form"], " dashicons-screenoptions " );

    }
    function action_activate_license(){
        check_admin_referer( 'el-license' );
        $license_key=!empty($_POST['el_license_key'])?sanitize_text_field(wp_unslash($_POST['el_license_key'])):"";
        $license_email=!empty($_POST['el_license_email'])?sanitize_email(wp_unslash($_POST['el_license_email'])):"";
        update_option("KitFunnelCRM_lic_Key",$license_key) || add_option("KitFunnelCRM_lic_Key",$license_key);
        update_option("KitFunnelCRM_lic_email",$license_email) || add_option("KitFunnelCRM_lic_email",$license_email);
        update_option('_site_transient_update_plugins','');
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }
    function action_deactivate_license() {
        check_admin_referer( 'el-license' );
        $message="";
	    $main_lic_key="KitFunnelCRM_lic_Key";
	    $lic_key_name =Kit_Funnel_C_R_M_Base::get_lic_key_param($main_lic_key);
        if(Kit_Funnel_C_R_M_Base::remove_license_key(__FILE__,$message)){
            update_option($lic_key_name,"") || add_option($lic_key_name,"");
            update_option('_site_transient_update_plugins','');
        }
        wp_safe_redirect(admin_url( 'admin.php?page='.$this->slug));
    }
    function activated(){
        ?>
        <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
            <input type="hidden" name="action" value="KitFunnelCRM_el_deactivate_license"/>
            <div class="el-license-container">
                <h3 class="el-license-title"><i class="dashicons-before dashicons-screenoptions"></i> <?php esc_html_e("KitFunnel CRM","kitfunnel-crm");?> </h3>
                <hr>
                <ul class="el-license-info">
                <li>
                    <div>
                        <span class="el-license-info-title"><?php esc_html_e("Status","kitfunnel-crm");?></span>

                        <?php if ( $this->response_obj->is_valid ) : ?>
                            <span class="el-license-valid"><?php esc_html_e("Valid","kitfunnel-crm");?></span>
                        <?php else : ?>
                            <span class="el-license-valid"><?php esc_html_e("Invalid","kitfunnel-crm");?></span>
                        <?php endif; ?>
                    </div>
                </li>

                <li>
                    <div>
                        <span class="el-license-info-title"><?php esc_html_e("License Type","kitfunnel-crm");?></span>
                        <?php echo esc_html($this->response_obj->license_title,"kitfunnel-crm"); ?>
                    </div>
                </li>

               <li>
                   <div>
                       <span class="el-license-info-title"><?php esc_html_e("License Expired on","kitfunnel-crm");?></span>
                       <?php echo esc_html($this->response_obj->expire_date,"kitfunnel-crm");
                       if(!empty($this->response_obj->expire_renew_link)){
                           ?>
                           <a target="_blank" class="el-blue-btn" href="<?php echo esc_url($this->response_obj->expire_renew_link); ?>">Renew</a>
                           <?php
                       }
                       ?>
                   </div>
               </li>

               <li>
                   <div>
                       <span class="el-license-info-title"><?php esc_html_e("Support Expired on","kitfunnel-crm");?></span>
                       <?php
                           echo esc_html($this->response_obj->support_end,"kitfunnel-crm");;
                        if(!empty($this->response_obj->support_renew_link)){
                            ?>
                               <a target="_blank" class="el-blue-btn" href="<?php echo esc_url($this->response_obj->support_renew_link); ?>">Renew</a>
                            <?php
                        }
                       ?>
                   </div>
               </li>
                <li>
                    <div>
                        <span class="el-license-info-title"><?php esc_html_e("Your License Key","kitfunnel-crm");?></span>
                        <span class="el-license-key"><?php echo esc_attr( substr($this->response_obj->license_key,0,9)."XXXXXXXX-XXXXXXXX".substr($this->response_obj->license_key,-9) ); ?></span>
                    </div>
                </li>
                </ul>
                <div class="el-license-active-btn">
                    <?php wp_nonce_field( 'el-license' ); ?>
                    <?php submit_button('Desactivar'); ?>
                </div>
            </div>
        </form>
    <?php
    }

    function license_form() {
        ?>
    <form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
        <input type="hidden" name="action" value="KitFunnelCRM_el_activate_license"/>
        <div class="el-license-container">
            <h3 class="el-license-title"><i class="dashicons-before dashicons-screenoptions"></i> <?php esc_html_e("KitFunnel CRM","kitfunnel-crm");?></h3>
            <hr>
            <?php
            if(!empty($this->show_message) && !empty($this->license_message)){
                ?>
                <div class="notice notice-error is-dismissible">
                    <p><?php echo esc_html($this->license_message,"kitfunnel-crm"); ?></p>
                </div>
                <?php
            }
            ?>
            <p><?php esc_html_e("Ingresa tu clave de licencia y correo electrónico de compra para activar KitFunnel CRM y habilitar las actualizaciones.","kitfunnel-crm");?></p>
			<p><br></p>

            <div class="el-license-field">
                <label for="el_license_key"><?php echo esc_html("Código de licencia","kitfunnel-crm");?></label>
                <input type="text" class="regular-text code" name="el_license_key" size="50" placeholder="xxxxxxxx-xxxxxxxx-xxxxxxxx-xxxxxxxx" required="required">
            </div>
            <div class="el-license-field">
                <label for="el_license_key"><?php echo esc_html("Email","kitfunnel-crm");?></label>
                <?php
                    $purchase_email   = get_option( "KitFunnelCRM_lic_email", get_bloginfo( 'admin_email' ));
                ?>
                <input type="text" class="regular-text code" name="el_license_email" size="50" value="<?php echo esc_html($purchase_email); ?>" placeholder="" required="required">
                <div><small><?php echo esc_html("Agrega el correo electrónico de registro cuando adquiriste KitFunnel.","kitfunnel-crm");?></small></div>
            </div>
            <div class="el-license-active-btn">
                <?php wp_nonce_field( 'el-license' ); ?>
                <?php submit_button('Activar ahora'); ?>
            </div>
        </div>
    </form>
        <?php
    }
}

new KitFunnelCRM();