<div class="helpContainer">

    <h3>Message</h3>
    <p>The text of the notification that will be sent to the user. This text will also appear as the title of the message in the Alert Inbox in the app.</p>

    <h3>Action</h3>
    <p>Selecting an action tells the phone what the push notification does when the user opens it. Tapping a notification can open a blog post, open a share screen or perform no action.</p>
    
    <h4>Push to Post</h4>
    <p class="indented">Opens a post within the app when the user opens the notification. You will need to know the unique numerical Post ID from your content management system (CMS). Please refer to your CMS to find instructions for locating the Post ID.</p>


    <h4>Push to Share</h4>
    <p class="indented">Opens a share screen within the app when the user opens the notification. You will need to include preview text for the share screen, a link to be shared (beginning with http://), description text to accompany the link if shared on Facebook and the complete text for the tweet to be shared on Twitter. Tweets should include a shortened link, hashtags, @mentions and your Twitter handle.</p>


    <h4>None</h4>
    <p class="indented">The notification does not include an action. After the user reads the message, they dismiss it without further action. This is useful for sending informational alerts.</p>

    <h3>Recipients</h3>
    <p class="indented">Determines which audience will receive the push notification <i>and</i> which messages will appear in a user's Alert Inbox. Notifications can be sent to all users or can be segmented by tags. The Alert Inbox is filtered by a user’s tags. </p>

    <h4>Broadcast</h4>
    <p class="indented">Sends the notification to all devices opted in to receive push notifications. All users will see the message in the Alert Inbox.</p>

    <h4>Tag</h4>
    <p class="indented">Specifies that the notification is sent only to users associated with at least one of the tags you choose. Begin typing the name of the tag in the search bar and select from the choices that appear. If you add multiple tags, the push notification will be sent to all users who have any of the tags assigned to them. For example, you could send a notification to users who “Have Tag 1 <i>or</i> Tag 2 <i>or</i> Tag 3” by selecting all three tags from the drop down.</p>
    <p class="indented"> If a user has more than one tag assigned and you send a separate notification to each tag, the user will receive it twice. If you want to send to multiple tags where there might be overlap, see the segments section below.</p>
    <p class="indented">Notifications sent using tags only appear in the Alert Inbox of users who have that tag associated with them. </p>

    <h4>Single</h4>
    <p class="indented">Allows you to send the push notification to a unique device identified by an ID, which you can find in the Mobile User Export from the Reports tab above. This will not appear in the Alert inbox.</p>

    <h4>Segment</h4>
    <p class="indented">Uses more advanced tagging to send the notification to users who have multiple tags assigned to them. For example, you could send a notification to users who "Have Tag 1 <i>and</i> Tag 2 but <i>not</i> Tag 3," ensuring the user would only receive the notification once. If you want to create and use a segment, contact your client manager at Winning Mark, who will create the segment and make it available for you in the drop down menu. Messages sent to segments will appear in the Alert Inbox of all users with any of the tags in the segment.</p>
</div>