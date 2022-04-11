<?php
use Mcisback\WpPlugin\Helpers\Settings;
?>

<h2 class="text-center">Welcome To Settings Page</h2>

<div class="settings-form">
    <form x-data="settingsform" x-on:submit.prevent="submitForm" method="POST">
        <div class="mb-3" x-show="showMessage" x-transition>
            <div 
                x-text="message" 
                class="alert alert-success text-bold" 
                :class="success ? 'alert-success' : 'alert-danger'"
                role="alert"></div>
        </div>
        <div class="mb-3">
            <label for="vTigerBaseUrl" class="form-label">VTIGER_BASE_URL</label>
            <input 
                type="text" 
                class="form-control" id="vTigerBaseUrl" name="baseUrl" x-model="formData.baseUrl" aria-describedby="vTigerBaseUrlHelp" required>
            <div id="vTigerBaseUrlHelp" class="form-text">We'll never share your email with anyone else.</div>
        </div>
        <div class="mb-3">
            <label for="vTigerUsername" class="form-label">VTIGER_USERNAME</label>
            <input 
                type="text" class="form-control" 
                id="vTigerUsername" name="username" x-model="formData.username" required>
        </div>
        <div class="mb-3">
            <label for="vTigerAccessKey" class="form-label">VTIGER_ACCESSKEY</label>
            <input 
                type="password" class="form-control"
                id="vTigerAccessKey" name="accessKey" x-model="formData.accessKey" required>
        </div>
        <button type="submit" class="btn btn-primary">Save</button>
        <button type="button" class="btn btn-primary" x-on:click="testConnection()">Test Connection</button>
    </form>

    <script>
        document.addEventListener('alpine:init', () => {
            console.log('alpine:init starting')
            
            Alpine.data('settingsform', () => ({
                showMessage: false,
                message: '',
                success: true,
                formData: {
                    baseUrl: '<?php echo Settings::gI()->get('VTIGER_BASE_URL'); ?>',
                    username: '<?php echo Settings::gI()->get('VTIGER_USERNAME'); ?>',
                    accessKey: '<?php echo Settings::gI()->get('VTIGER_ACCESSKEY'); ?>'
                },
                testConnection() {
                    const data = {
                        'action': 'TestVTigerConnection',
                        'data': btoa(unescape(encodeURIComponent(JSON.stringify(this.formData))))
                    };

                    jQuery.post(ajaxurl, data, response => {
                        const { success, data: rawData } = response;
                        const { msg, data } = rawData;

                        console.log("RESPONSE:");
                        console.dir(response);

                        this.success = success;

                        console.log("SUCCESS: ", success, msg);
                        this.showMessage = true;
                        this.message = msg;

                        //location.reload();
                    
                    });
                },
                submitForm() {
                    const data = {
                        'action': 'UpdateSettings',
                        'data': btoa(unescape(encodeURIComponent(JSON.stringify(this.formData))))
                    };

                    jQuery.post(ajaxurl, data, response => {
                        const { success, data: rawData } = response;
                        const { msg, data } = rawData;

                        console.log("RESPONSE:");
                        console.dir(response);

                        this.success = success;

                        console.log("SUCCESS: ", success, msg);
                        this.showMessage = true;
                        this.message = msg;

                        //location.reload();
                    
                    });
                    /* const data = {
                        action: 'my_updateSettings',
                        data: btoa(unescape(encodeURIComponent(JSON.stringify(this.formData)))),
                        // <?php echo PLUGIN_ID . '_nonce'; ?>: <?php echo PLUGIN_ID . '_form'; ?>.nonce,
                        // _ajax_nonce: <?php echo PLUGIN_ID . '_form'; ?>.nonce 
                    }

                    console.log('FormData: ')
                    console.log(data)
                    console.log('AJAXURL: ', ajaxurl)

                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: {
                            // 'Accept': 'application/json',
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: data
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log('RESPONSE DATA: ', data)
                    }) */
                }
            }))
        })
    </script>
</div>