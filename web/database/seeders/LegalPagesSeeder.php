<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        LegalPage::updateOrCreate(
            ['slug' => 'privacy-policy'],
            [
                'title' => 'Privacy Policy',
                'content' => '<h2>Privacy Policy</h2>
<p>Last updated: January 1, 2026</p>
<p>Welcome to Digital Store ("we," "our," or "us"). We are committed to protecting your personal information and your right to privacy. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website and purchase our digital products.</p>

<h2>Information We Collect</h2>
<p>We collect personal information that you voluntarily provide to us when you register an account, make a purchase, or contact us for support. This information may include your name, email address, phone number, and payment-related details such as transaction IDs and sender numbers. We also automatically collect certain information when you visit our website, including your IP address, browser type, operating system, and browsing behavior through cookies and similar technologies.</p>

<h2>How We Use Your Information</h2>
<p>We use the information we collect to process your transactions and deliver purchased digital products to your email address. Your information helps us provide customer support, send order confirmations and updates, and improve our website and product offerings. We may also use your information to detect and prevent fraud, comply with legal obligations, and communicate with you about promotions or updates if you have opted in to receive such communications.</p>

<h2>Information Sharing</h2>
<p>We do not sell, trade, or rent your personal information to third parties. We may share your information with trusted service providers who assist us in operating our website, conducting our business, or servicing you, as long as those parties agree to keep this information confidential. We may also release your information when we believe release is appropriate to comply with the law, enforce our site policies, or protect ours or others rights, property, or safety.</p>

<h2>Data Security</h2>
<p>We implement a variety of security measures to maintain the safety of your personal information. Your personal data is stored in secured networks and is only accessible by a limited number of authorized personnel who have special access rights. However, no method of transmission over the Internet or electronic storage is one hundred percent secure, and we cannot guarantee absolute security.</p>

<h2>Your Rights</h2>
<p>You have the right to access, correct, or delete your personal information at any time. You may also opt out of receiving marketing communications from us. To exercise any of these rights, please contact us at support@digitalstore.com. We will respond to your request within a reasonable timeframe.</p>

<h2>Changes to This Policy</h2>
<p>We may update this Privacy Policy from time to time. We will notify you of any changes by posting the new policy on this page and updating the "Last updated" date. You are advised to review this policy periodically for any changes.</p>

<h2>Contact Us</h2>
<p>If you have any questions about this Privacy Policy, please contact us at support@digitalstore.com.</p>',
            ]
        );

        LegalPage::updateOrCreate(
            ['slug' => 'terms'],
            [
                'title' => 'Terms & Conditions',
                'content' => '<h2>Terms & Conditions</h2>
<p>Last updated: January 1, 2026</p>
<p>Welcome to Digital Store. By accessing or using our website and purchasing our digital products, you agree to be bound by these Terms and Conditions. Please read them carefully before making a purchase.</p>

<h2>General Conditions</h2>
<p>We reserve the right to refuse service to anyone for any reason at any time. You understand that your content (not including credit card information) may be transferred unencrypted. Products are available exclusively online through our website and are subject to return or exchange only according to our Refund Policy.</p>

<h2>Product Availability and Accuracy</h2>
<p>We are not responsible if information made available on this site is not accurate, complete, or current. The material is provided for general information only and should not be relied upon as the sole basis for making decisions. Any reliance on the material on this site is at your own risk. We reserve the right to modify the contents of this site at any time but have no obligation to update any information on our site.</p>

<h2>Purchases and Payment</h2>
<p>All purchases of digital products are final. Once a digital product has been delivered to your email address, it is considered fulfilled. Prices for our products are subject to change without notice. We reserve the right to modify or discontinue any product at any time. Payment must be completed through the available payment methods before product delivery. You are responsible for providing accurate payment information including correct sender numbers and transaction IDs.</p>

<h2>Digital Product License</h2>
<p>Upon successful payment, you are granted a non-exclusive, non-transferable license to use the purchased digital product for your personal or internal business use. You may not redistribute, resell, share, or publicly display any digital product purchased from our store. Violation of this license may result in termination of your account and legal action.</p>

<h2>User Accounts</h2>
<p>You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account. We reserve the right to refuse service, terminate accounts, remove or edit content, or cancel orders at our sole discretion.</p>

<h2>Limitation of Liability</h2>
<p>In no case shall Digital Store, our directors, officers, employees, or agents be liable for any injury, loss, claim, or any direct, indirect, incidental, punitive, special, or consequential damages of any kind. Our liability to you for any cause whatsoever will at all times be limited to the amount you paid to us for the product in question.</p>

<h2>Indemnification</h2>
<p>You agree to indemnify, defend, and hold harmless Digital Store and our affiliates from any claim or demand, including reasonable attorneys fees, made by any third party due to or arising out of your breach of these Terms or your violation of any law or the rights of a third party.</p>

<h2>Governing Law</h2>
<p>These Terms are governed by and construed in accordance with applicable laws. Any disputes arising under these Terms shall be resolved in the appropriate courts of the applicable jurisdiction.</p>

<h2>Contact</h2>
<p>Questions about the Terms should be sent to support@digitalstore.com.</p>',
            ]
        );

        LegalPage::updateOrCreate(
            ['slug' => 'refund-policy'],
            [
                'title' => 'Refund Policy',
                'content' => '<h2>Refund Policy</h2>
<p>Last updated: January 1, 2026</p>
<p>At Digital Store, we strive to ensure your complete satisfaction with every purchase. Because we sell digital products, our refund policy is different from traditional physical goods. Please read the following policy carefully before making a purchase.</p>

<h2>General Refund Terms</h2>
<p>All sales of digital products are generally final. Due to the nature of digital goods, once a product has been delivered to your email address or downloaded, we are unable to offer a full refund. However, we understand that exceptional circumstances may arise, and we handle each refund request on a case-by-case basis.</p>

<h2>Eligible Refund Scenarios</h2>
<p>You may be eligible for a refund if the digital product you purchased is defective, corrupted, or cannot be opened or used as intended. If the product delivered is significantly different from the description provided on our website, you may request a refund within seven days of purchase. If you accidentally purchased the same product twice, we will issue a refund for the duplicate transaction upon verification.</p>

<h2>Non-Refundable Scenarios</h2>
<p>Refunds will not be issued if you simply change your mind after purchasing a digital product. Requests made after seven days of the original purchase date will not be considered. If you purchased the wrong product by mistake but received the correct file as described, a refund will not be granted. Refund requests without a valid reason or without supporting evidence will be declined.</p>

<h2>How to Request a Refund</h2>
<p>To request a refund, please send an email to support@digitalstore.com with your order details including the product name, purchase date, transaction ID, and a detailed explanation of the issue. Our team will review your request and respond within three to five business days. We may request additional information or evidence to process your claim.</p>

<h2>Refund Processing</h2>
<p>If your refund is approved, the refund will be processed through the same payment method used for the original purchase. Please allow seven to ten business days for the refund to reflect in your account. You will receive an email confirmation once the refund has been processed successfully.</p>

<h2>Chargebacks</h2>
<p>We encourage you to contact us directly before initiating a chargeback with your bank or payment provider. Most issues can be resolved quickly through our support team. Filing a chargeback without contacting us first may result in delayed resolution and additional fees.</p>

<h2>Exceptions</h2>
<p>We reserve the right to make exceptions to this refund policy at our sole discretion. Any exceptions will be handled individually and do not set a precedent for future transactions.</p>

<h2>Contact Us</h2>
<p>If you have any questions about our Refund Policy, please contact us at support@digitalstore.com. We are here to help and ensure you have the best possible experience with our products.</p>',
            ]
        );
    }
}
