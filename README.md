# xf_1.5_hide_hack

## Original Work:
This is a fork of the Hide Hack from Aayush
The original work can be found here: https://xenforo.com/community/resources/aayush-hide-hack-v1.1618/

## Changes: 
- fixed the problem with being only able to see links in hide-thanks when the user is in the last 5 ones that liked the post
- changed the behaviour how the addon checks if the user has liked a post

## Description:
This addon gives users the ability to hide their content using BbCode.

* Six different BBCode to Hide Contents.
- [HIDE-REPLY] -> Content within this tag is not shown to user until he/she replies in that thread.
- [HIDE-POSTS] -> Content within this tag can only be seen by people's who have more posts than defined number of posts.
- [HIDE-THANKS] -> Content within can only be seen after the user click's "Like" button.
- [HIDE-REPLY-THANKS] -> Content within this tag is not shown to user until he/she either reply or press "Like" button.
- [SHOWTOGROUPS] -> Content within this tag is only shown to defined usergroups.
- [HIDE] -> This tag can be mapped to any of the above BBCodes. (Default - [HIDE-REPLY])

* No Template or File Edits.
* Case-Insensitive Tags.
* Uses AJAX technology for (HIDE-REPLY, HIDE-THANKS, and HIDE-REPLY-THANKS).
* Global and Per-Forum Basis XenForo settings for each tag.
* Ability to specify usergroups that can always see hidden content.
* There is no way to go around the tags. They are parsed correctly everywhere, including:
** View Thread
** Search
** NewsFeed
** Thread Preview
** Email
** Meta Description
** Quote

## Installation:
- Extract attached zip and upload the contents of the "upload" to your XF root.
- Install from uploaded file: addon-vfcoders_hide_hack.xml
- Set options for the hide hack in administration control panel.
- Set usergroup permissions of each group for hide hack.
- Disable Cache BB Code output
