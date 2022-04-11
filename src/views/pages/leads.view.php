<?php
use Mcisback\WpPlugin\Helpers\Settings;

$logPath = PLUGIN_PATH . '/Logs/leads.json';

/* $lead = [
    "firstname" => "",
    "lastname" => "",
    "phone" => "",
    "leadsource" => "",
    "leadstatus" => "",
    "cf_851" => "",
    "cf_853" => "",
    "assigned_user_id" => "",
    "lane" => "",
    "city" => "",
    "state" => "",
    "code" => "",
    "country" => "",
];
 */
?>

<h2 class="text-center">Welcome To Leads Page</h2>

<div class="leads-page">
    
    <pre>

        <?php if( file_exists( $logPath ) ): ?>

        <?php
            echo file_get_contents( $logPath );
        ?>

        <?php else: ?>
        <?php echo "No Leads Yet"; ?>
        <?php endif; ?>

    </pre>
    <script>
        document.addEventListener('alpine:init', () => {
            console.log('alpine:init starting')
            
            Alpine.data('leadspage', () => ({
                
            }))
        })
    </script>
</div>