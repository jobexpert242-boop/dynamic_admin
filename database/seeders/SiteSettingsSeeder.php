<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('site_settings')->insert([
            'id' => 1,
            'logo' => 'site/logo/IfPzpxrTvUfic0M2proUGKAdko13fXvAgDJjEUVU.jpg',
            'favaicon' => 'site/favaicon/sKQqIwnQkPUBblLtOp9SDBEOpY0AUMomV7NxPMP8.jpg',
            'docs' => '<ol><li><p style="text-align: left;"><strong>Admin Use Guide Step by Step.</strong></p></li></ol><ol><li><p style="text-align: left;">1.First Create Some Permission Related the Route this You Want to Create.</p></li><li><p style="text-align: left;">2.Going to Roles give the Permission.</p></li><li><p style="text-align: left;">3.Going to Users and specific user/admin Permission.</p></li><li><p style="text-align: left;">4.Create menu and give the Permission.</p></li></ol><p></p>',
            'created_at' => '2025-11-16 03:16:11',
            'updated_at' => '2025-12-08 02:44:13',
            'inv_termes' => '<h2><strong>Terms and Conditions on Invoices:</strong></h2><p># Domain – 100% advance payment for hosting and 25% advance payment for web/software development to be confirmed. Advance payment is nonrefundable.</p><p># Domain, Hosting and website should be registered to client name. ComitsBD will give only technical service to client.</p><p># If a client misuses ComitsBD,s&nbsp;product (eg domain/hosting/website), ComitsBD is not responsible for the client\'s incident.</p><p># All the source code of project and cPanel access will be given to Client by ComitsBD.</p><p># If the client requires any new module, ComitsBD will develop the system and subsequently install and provide support services and maintenance. Additional charges as Implementation Cost will be determined based on bilateral discussion.</p><p># In this case, ComitsBD will transfer the source code to the client. After the free service period the client will have the freedom to choose the company for further development/maintenance/service/up gradation of the software.</p>',
            'tax' => '5',
            'inv_prefix' => 'INV_',
            'company_location' => 'H 375, 3rd Floor, R 28, Mohakhali DOHS, Bangladesh',
            'company_contact' => '+8801711257224',
            'company_email' => 'support@comitsbd.net',
            'company_web' => 'https://comitsbd.com/',
            'company_facebook' => null,
            'company_youtube' => null,
            'company_linkdin' => null,
            'site_termes' => null,
            'site_about' => null,
            'currency' => 'BDT',
        ]);
    }
}
