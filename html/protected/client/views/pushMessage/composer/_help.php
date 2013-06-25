<div id="demo">

    <p></p>

    <h3>Message</h3>
    <p>The message is the text of the notification that will be sent to the user. This text will also appear as the title of message in the Alert Inbox in the app.</p>

    <h4>Action</h4>
    <p>Selecting an action will tell the phone what the push notification will do when the user opens it. In your app, tapping a notification can perform no action, open a blog post or open a share screen.

    </p>

    <h4>None</h4>
    <p>Selecting None means the notification does not include an action. After the user reads the message, they dismiss it without further action. This is useful for sending informational alerts. 

    </p>

    <h4>Push to Post</h4>

    <p>Selecting Push to Post means when the user taps to view the notification, a post will open within the app. You will need to know the unique numerical Post ID from your content management system (CMS). Please refer to your CMS to find instructions for locating the Post ID.  </p>


    <h4>Push to Share</h4>
    <p>Selecting Push to Share means when the user taps to view the notification, a share screen will open within the app. You will need to include preview text for the share screen, a link to be shared (beginning with http://), description text to accompany the link when shared on Facebook and the complete text for the tweet to be shared on Twitter. Tweets should include a shortened link, hashtags, @mentions and your Twitter handle.</p>


    <h3>Recipients</h3>
    <p>Choosing a recipient determines the audience that receives the push notification as well as which messages will appear in a user's Alert Inbox. Push notifications can be sent to all users or can be segmented by tags. The Alert Inbox can be filtered by tags. 
    </p>

    <h4>Broadcast</h4>
    <p>Selecting Broadcast will send the notification to all devices opted-in to receive push notifications from your app. All users will see the message in the Alert Inbox. 
    </p>

    <h4>Tag</h4>
    <p>Selecting Tag allows you to specify that the notification will only be sent to users associated with at least one of the tags you choose. Begin typing the name of the tag in the search bar and select from the choices that appear. You can add multiple tags and the push notification will be sent to all users who have any of the tags assigned to them. 
        <br/>
        Note that if a user has more than one tag assigned and you send the notification to both tags, the user will receive it twice. If you want to send to multiple tags where there might be overlap, use the segments section.  
        <br/>
        If you send the notification using tags, the message will only appear in the Alert Inbox of users who have that tag associated with them. 

    </p>


    <h4>Single</h4>
    <p>Selecting Single allows you to send the push notification to a unique device identified by an ID.
    </p>
    
    <h4>Segment</h4>
    <p>Selecting Segment allows you to use more advanced tagging to send the notification to users who may have multiple tags assigned to them. For example, you could send a notification to users who "Have Tag 1 and Tag 2 but not Tag 3" and the user would only receive the notification once. If you want to use a segment, contact your client manager at Winning Mark, who will create the segment and it will be available for you in the drop down menu. </p>

</div>