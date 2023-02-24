<?php
return apply_filters('wilcity/filter/wiloke-listing-tools/configs/push-notifications', [
    'admin' => [
        'someoneSubmittedAListingToYourSite' => [
            'title' => 'Author submitted a new listing Notifications',
            'desc' => 'Get notified after author submitted a new listing to your site',
            'status' => 'on',
            'msg' => '%userName% just submitted a new listing to your site. Listing Type: %postType%, Listing Name: %postTitle%, Listing ID: %postID%, Submitted Date: %postDate%'
        ],
        'someoneSubmittedAProductYourSite' => [
            'title' => 'Author submitted a new product Notifications',
            'desc' => 'Get notified after author submitted a new product to your site',
            'status' => 'on',
            'msg' => '%userName% just submitted a new product to your site. Product Name: %postTitle%, Product ID: %postID%, Submitted Date: %postDate%'
        ],
    ],
    'customers' => [
        'toggleAll' => [
            'title' => 'Enable Notifications',
            'desc' => 'Select disable to turn off all notifications',
            'msg' => ''
        ],
        'followerPublishedNewListing' => [
            'title' => 'Author Posted New Listing Notifications',
            'desc' => 'Get notified when author who you are following posts a new listing',
            'status' => 'on',
            'msg' => '%userName% just published a new post %postTitle%. %postExcerpt%'
        ],
        'listingStatus' => [
            'title' => 'Listing Status Notifications',
            'desc' => 'Get notified when your listing status is changed. Eg: Your listing has been approved.',
            'status' => 'on',
            'msg' => 'Your listing %postTitle% has been changed from %beforeStatus% to %afterStatus%'
        ],
        'privateMessages' => [
            'title' => 'Private Message Notifications',
            'desc' => 'Get notified when you receive a private messages',
            'status' => 'on',
            'msg' => '%senderName%: %message%'
        ],
        'eventComment' => [
            'title' => 'Event Comment Notifications',
            'desc' => 'Get notified when someone leaves a comment on your event',
            'msg' => '%userName% just left a comment on %postTitle%: %commentExcerpt%',
            'status' => 'on'
        ],
        'review' => [
            'title' => 'Review Notifications',
            'desc' => 'Get notified when someone leaves a review on your listing',
            'msg' => 'Rating %averageRating% %userName% just left a review on %postTitle%: %reviewExcerpt%',
            'status' => 'on'
        ],
        'reviewDiscussion' => [
            'title' => 'Review Discussion Notifications',
            'settingDesc' => 'You can use %averageRating% as a placeholder in the notification message',
            'desc' => 'Get notified when someone leaves a discussion on your review',
            'msg' => '%userName% just left a comment on %postTitle%: %reviewExcerpt%',
            'status' => 'on'
        ],
        'newFollowers' => [
            'title' => 'New Followers Notifications',
            'desc' => 'Get notified when someone new starts following you',
            'status' => 'on',
            'msg' => '%userName% is following you now'
        ],
        'claimApproved' => [
            'title' => 'Claim Approved Notifications',
            'desc' => 'Get notified after your claim is approved',
            'status' => 'on',
            'msg' => 'Congratulations! %postTitle% claim has been approved'
        ],
        'productPublished' => [
            'title' => 'Product Published Notifications',
            'desc' => 'Get notified after your product is published',
            'status' => 'on',
            'msg' => 'Congratulations! %postTitle% is ready for selling'
        ],
        'productReview' => [
            'title' => 'Product Review Notifications',
            'desc' => 'Get notified when someone reviews your product',
            'status' => 'on',
            'msg' => 'Rating %rating% Comment: %reviewExcerpt%',
        ],
        'soldProduct' => [
            'title' => 'Sale Notifications',
            'desc' => 'Get notified when someone purchases your product',
            'status' => 'on',
            'msg' => 'Congratulations! You made a sale from %postTitle%. Order ID: %orderID%',
        ],
        'paymentDispute' => [
            'title' => 'Payment Dispute',
            'desc' => 'There is a dispute issue in a payment session',
            'status' => 'on',
            'msg' => 'There was a dispute with %paymentID% Payment ID. Please contact %adminEmail% to resolve this issue',
        ]
    ]
]);
