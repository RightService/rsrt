# The Right Service at the Right Time

----
## Overview
RSRT is a Drupal 7 that has been architected to display information about Services (nodes) in a number of Service Types (taxonomy), related to one or more Counties (organic groups) in a given state. Each Service must have a Provider (node) and may have an Application (node). The Providers and Applications are meant to be re-used on multiple Services. The users on the site are mostly anonymous, but they may register in order to remember the items in their Basket (flag). A user may flag a County as her preferred County, which changes any time they visit a new URL prefixed section of the site. Users may have the role Librarian, Provider, Administrator or Content Editor. There is a Temporary user role as well, which is removed from a user when she confirms her email address. A user may gain posting and editing permissions to a County by becoming a member of a County group, and they may also have a role as a member of a county. Privileged users (Librarians, Providers, Admins and Content Editors) may view a Dashboard, which appears in the Mobile Freindly Navigation Bar under the RSRT heading. The site collects statistics about which services are being viewed in a Piwik Analytics site, which is hosted on the same server. The site may also be translated into multiple languages, such as Spanish, Vietnamese, and Hatian Creole.

----
## County (node type, organic group)
1. If a Library has a link to RSRT on their website, they should link directly to the County page.
2. The county URL is created with Pathauto automatic URL Aliases.
3. The Persistent URL (PURL) API is used to allow a County context to be set on most pages of the site, to keep a county active from one page to the next. Even if a service belongs to more than one county, the footer and breadcrumb should reflect the current county, and internal links should be rewritten with the current county prefix. There is a small custom module (rsrt_county) that handles the integration between purl and RSRT, while still allowing localization prefixes.
4. Counties have a field for storing IP address ranges. The idea here is to automatically redirect a user to the county's landing page if they are visiting the home page from a set of IP addresses inside a Library or other facility.
5. A county has a logo that appears in a context-sensitive block in the footer of the site.
6. Counties are an Organic Groups "group type", meaning you can associate nodes with this group if it is designated as "group content". Users who are members of the group (Providers and Librarians) may be given permission to edit nodes that are part of their own group without giving them permission to edit every Service on the site. There are also a set of group roles that can be assigned to a user to further keep the roles separate. For example, adding a Provider to a group does little more than allow them to post content to the group. However, adding a user as a Librarian role gives her a bit more access, she would be able to edit nodes that are not her own. Adding a user as an Administrator member is useful if more than one user needs extra editing permission of a group, but they are not a Site administrator.

----
## Services (node type, group content)
1. Services are the key content type on the site. End-users are searching for Services. Most views on the site display services, most content editing is around services.  
2. A service has relatively simple information, the most basic service is just a title and description. The Application and Provider are often where contact info, addresses and file downloads come from. A service may have its own address, like a local office.
3. A service has Qualification Questions, also known as Tier 2 Questions. If a Service has specific requirements on a user before they can be eligible for a service, the qualification questions will reflect that. For example, some services are only for non-English speakers, so the person creating the service would choose "Must not speak English". Then when a user searches for that service, they would not check the "I speak English" box on the search page.
4. A Service also has a Checklist, showing a list of items (usually documents) a user must bring with them if applying at a Brick and Mortar location. Things like a Driver's license, proof of residence, a Green Card, etc.
5. See Counties, Providers, Applications, Service Types and Reach for more functionality around services, as well as Basket and Ignored Services flags.

----
## Providers (node type, group content)
A Provider is the organization that offers a service. Providers store a logo, website, contact information and an address, as well as a description of the organization. Most of the contact info here is meant to be shown publicly. There is also a section for personal contact information. A Provider node is displayed within a Service node page, and the address will display a map if the service does not have a location of its own.

----
## Applications (node type, group content)
Applications are meaningless unless they are attached to a service. They represent the way someone asks to use a service, or set of services. Sometimes these applications change from year to year or region to region, so they were added to a separate content type to make editing them easier in the long term. An application stores a link in the case of a web-based application, or a document in the case of a printable means of applying for a service.

----
## Service Types (Vocabulary)
The service types have a Hierarchy. The top level Types are the "Tier 1" questions. It is the answer to the question "I am looking for what kind of services?" Each Tier 1 has an icon associated with it, that appears on the service types page (the landing page for a county. The landing page also shows all of the subcategories under a term, which are still technically Tier 1 questions. Tier 2 questions are the Qualification Questions on the nodes, and Tier 3 used to be Service-specific Qualifications in Drupal 6.

----
## Reach (Vocabulary)
Currently on or off - i.e. Statewide, or not statewide. This term relies on a Rule to enforce the Statewide reach. Could be extended to deal with Regional use cases - e.g. Central Florida, South Florida, Panhandle (since counties don't really always make the most sense)

----
## Help Pages (node type)
Used only on the back end dashboard to store files to download (like a PSD that can be printed by a Library as collateral to promote Right Service) or other how-to documentation. There are two types of help pages, Tutorials and Assets, implemented with a Select list.

----
## Page (node type)
Pages are used for a few places where we needed things like links to a survey or other meta-information that was not a View, Search or Panel, and not one of the 3 main content types of Provider, Service or Application.

----
## Story (node type)
The idea was to allow the Webmaster to update the Librarians and Providers about site news, and send them an email update, but still have a place to log the messages within the site. This functionality is not implemented at this time.

----
## Webform (node type)
There is one survey on the site that uses Webform. In Drupal 6, all Services were webforms, because we had Qualification Questions on each Service, but very few ever used this feature, so it was removed for Drupal 7.

----
## Basket (flag)
When a user adds a Service to her Basket, it will appear in the Basket view. A user may use the Basket to bookmark services the next time she logs in to the site, since many users access this site from a public computer.

----
## Ignored Services (flag)
A user may choose to remove a Service from search results by flagging a service with the ignore flag. The Ignored services appear on a view, so a service may be unflagged in case it was done by mistake.

----
## County (flag)
When a user goes to a County page (e.g. /orange) they trigger a function that flags that county and un-flags any other counties. This is convenient because flags work for anonymous users in Drupal 7, and we don't have to create user profiles for all the users (like we did in Drupal 6).

----
## Custom Modules
* **rsrt_address** (disabled) - allows you to change the format of the Address fields on display - creates plugins that have custom formatters for addresses, namely removing the Country, and changing the tags that wrap the address parts to keep the address on one line. There is a second formatter that hides the state, since in our case all the states are Florida. These formatters can be used together or separately, and are configured everywhere you use a formatters (Panels, Views, Content Type > Manage Display)
* **rsrt_blocks** - creates a block to display for logged-in users, county-specific footer, a print/email block, the Library Dashboard links, and the Footer message
* **rsrt_checkboxes** - Adds custom large image-based checkboxes using CSS and Javascript. Using the MIT-licensed jquery checkbox plugin: http://widowmaker.kiev.ua/checkbox
* **rsrt_county** - creates the Persistent URL provider that maps each county to a URL prefix. Provides an Organic Groups context handler. Provides an admin screen to let you set which URLs to include/exclude from PURL prefixes. Handles Geolocation with a Javascript sniffer based on your IP address. Includes a CSV mapping the counties in Florida to their Zip Codes
* **rsrt_exposedfilters** - works on the Services Search to change the filters which have 3 options (must have, must not have, doesn't matter) to two options, which can be handled with a single checkbox (is not "must have", or is not "must not have") since "doesn't matter" is true in either case.
* **rsrt_locale** - Integrates RSRT County prefixes with Locale prefixes - see http://drupal.org/node/1270894 - copied from https://www.drupal.org/node/1705692 - suggested patch at https://www.drupal.org/node/2279931
* **rsrt_menu** - Adds the RSRT section to the Navbar to provide administration links
* **rsrt_migrate** - developed to move all the data out of the Drupal 6 version into the Drupal 7 version of RSRT
* **rsrt_services** (disabled) - meant to hold default views for the site - probably should be implemented as Features (see Features Module)

---
## Responsive Theme  
The theme is a sub-theme of AdaptiveTheme, and has been created with several breakpoints reflecting different screen sizes and device capabilities. For example, the clickable state disappears on the home page, because image maps are not scalable.

---
## Panels
The panels module is used in several places to provide context-sensitive pages, or to do some layout on Service pages. Panels are also used to create Librarian Dashboards that are county-aware, so when you visit orange/library/dashboard you get different results than seminole/library/dashboard

---
## Admin Views
The default Content view has been overridden with a view. There is also a custom view at http://rightservicefl.org/admin/content/services that is useful for administering Services, and can export a CSV file of Service information

---
## Context
Blocks in the sidebar and in the header are placed via the Context module. This helps to show different sets of blocks to logged in vs. anonymous users. There are also additional blocks that only appear on the frontpage or on a services search page.

---
## Statistics
The Drupal core Statistics module is enabled, which is a potential performance bottleneck, though it does work via AJAX in the current version. This gives us rough view counts, but the heavy lifting is done by Piwik. However, once we added more than 50 counties, Piwik got less appealing, because the view for custom variables (counties) only showed the first 50 values. On the piwik configuration page, custom variables are sending information about the current county, zip code, service, provider, and user role. The Piwik Reports module makes it possible to pull piwik reports into Drupal - this needs to be implemented.

---
## Hierarchical Select
Used to allow content editors to drill down into Service Types and add multiple types to a Service. Also makes sure that if a user chooses a sub-category, like "Elderly", that the parent item "Daily Needs" is still added as well, otherwise it would not show up in search for Daily Needs.

---
## Multilingual
Most of the Multilingual settings are on autopilot, though new translations can be added through the interface. You can translate interface strings separately from things like Content, Menu Items, Taxonomy Terms, etc. It's also important to note that unless you are going to be translating a node, you should leave it "Language Neutral". 

---
## Organic Groups
Much has been said about Organic Groups in the County section above. Please know that having Organic groups makes creating new Views from scratch more challenging - you almost always have to add relationships - it's usually easier to copy an existing view and modify it.

---
## Entity Connect
Allow a user to create a new Provder node from within a Service node form.

---
## Environment Indicator
When you move to a new server, or your laptop, take a minute to set this up. Integrated with Mobile Friendly Navigation Bar

---
## Mobile Friendly Navigation (Navbar)
The Drupal 8 style Admin menu. I have a custom module that adds new items to it. Click the arrow at the far right to turn it into a vertical instead of horizontal menu. Changes colors based on Environment Indicator settings.

---
## Profile2 Registration Path
If they want people to be able to register as a Provider or as a Librarian instead of just a plain User, you will need to set up this module.

---
## LoginToboggan
Would allow users to register without going through email confirmation, and enforce the setting of the Temporary user role, and deleting temporary users if they never get confirmed.

---
## View Unpublished
You need this if Services don't get published right away, and you want people to be able to see their un-approved content.

---
## Rules
Allows you to script business rules (like Statewide Services, email notifications) without writing code. Can also be enabled/disabled by non-programmers. Might still be some Rules from the Drupal 6 site that need to get copied over.

---
## Search Configuration
Allows us to restrict search to just Services without having to go to Apache Solr.

---
## Honeypot, Spambot, Mollom, Captcha
We get lots of spam users on this site.

---
## External Links
They want to display a disclaimer every time you click an outside link.

--- EVA (Entity Views Attach)
## Allows us to put a view on a Provider node that shows all the Service nodes that refer back to this page.

---
## Views Field View
Used to show the subcategories inside a Service Type

---
## External Link Popularity
When you click an outside link, track it with the Voting API

---
## Views
The best thing about Drupal. Here is a hint at what these views are used for. Some of the views that say "Default" could be used for things elsewhere deep in the site, best not to mess with them unless you figure out where they are used.

* all services - admin page, exports a CSV of Services for downlaod and editing  
* Child Terms - on Service Types page  
* County Jump Menu - The home page, includes image map HTML  
* Current County - your county is "Orange"  
* External Link Popularity Log, External Link Popularity Summary, Popular links - default  
* External URLs - shown in admin areas  
* GeoCode - made so I could mass-encode Addresses after migration, can be disabled if it is empty  
* My Basket - Shows a user's basket  
* New County Users - latest user registration by group  
* New Provider Profiles - New users with provider role  
* New Provider User Registrations - same as above  
* New Services - part of Dashboard  
* OG all user group content - default OG view - good for copying  
* OG Content - default OG view - good for copying  
* OG Members, OG Members Admin, OG User Groups - default OG views, good for copying  
* other nodes by author, other nodes of type - example views  
* Provider Stats - stats for Providers  
* Providers - for site admin  
* Recently Updated Providers and Services - for dashboard  
* Related Pages - default  
* RSRT Glossary - Browse Services  
* RSRT Group Admin - Show a users groups   
* RSRT Group Content Links - for dashboard  
* RSRT My Content - for dashboard  
* Service Type - The primary Services Search page interface. AKA Tier 2 Questions.  
* Service Types - County Landing Pages, AKA Tier 1 Questions  
* Statewide - show all statewide services  
* Statewide Glossary - Alpha list of statewide  
* Term Summary - Default  
* Tutorials - show help pages on Dashboard  
