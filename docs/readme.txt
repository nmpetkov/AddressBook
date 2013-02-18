The idea for this module was inspired by the the Address Book of my Palm V I used for years.
I have tried to create a Zikula Address Book with all the features of the Palm version and to enhance it by adding some new features.
If you are an admin, please read the note below.

----------------------------------------
Zikula features:
----------------------------------------
- Module is compatible with Zikula 1.x
- Code and database handling use Zikula API
- Output is totally generated with pnRender
- Security checks
- Administration menu
- Automatic installation, activation and deletion

----------------------------------------
Address Book features
----------------------------------------
- free categories (name and sum of categories)
- free contact labels (name ans sum of labels)
- List by category/all categories
- List all contacts or A-Z mode
- 2 free configurable sort options
- free configurable list view
- full text search
- up to 5 contact labels per address
- free choice of main contact (shown in list view)
- unlimited optional custom fields
- special data types for custom fields
- setting for the width of custom textarea fields
- configurable main menu
- setting for input/output format for decimal numbers and date
- Note field for each contact
- Image support via mediashare
- Copy to clipboard feature (Internet Explorer only)
- AJAX Autofill company address
- Autodetect mail and url contact labels
- Global and/or private contacts
- Show zip before city (Europe) option
- Favourites
- Copy of contacts
- Save & copy feature

----------------------------------------
The Administration features step by step
----------------------------------------
1.  Title of this Address Book
    Enter here the headline of you address book
	
2.  Name display in list view and sort order for name information
    This is setting for the sort order and display of names in the 
	  list view, so you can change the default «Last Name, First Name»
	  to «First Name Last Name», which is default in some countries
	
3.  Special character (Umlauts) replacement for sort columns
    MySQL 4.x uses the Latin-1 character set as default. Most users have no
  	permission to change these settings. Therefore names and companies are 
  	now stored in special sort columns for correct sortings when special
  	characters (Umlauts) are used. There is an admin setting where you can
  	set up the character replacement to correct the sort for each language
	
4.  Personal address book mode
	  Use Address Book in different modes:
  	a) as a global Address Book (this switch is off)
  	b) as a personal Address Book for each registered user
	   (this switch is off)

5.  Do you want to use a prefix field?
	  New column Prefix/Title (Mr. Mrs. etc.). Values can be set in the
  	Administration and are presented with a selection field in the form. This 
  	column is visible only in the insert/edit form. You can disable it 
  	completely in the Administration. It was added for compatibility with 
  	future import/export extensions, particularly to export for serial 
  	letters	

6.  Do you want to use images/logos?
    AddressBook supports the module mediashare by Jorn Wildt.
    If you don't want to use images, you can deselect this switch.
	
7.  Google Maps API key
    Insert here your Google API key. If this field is left blank, then there
    is no support for Google Maps (also in the address contact form).
    You can get your own API key here:
    http://code.google.com/intl/en-EN/apis/maps/signup.html

8.  Zoom
    Here you define the default zoom factor of all shown google maps in the
    detail page

9.  Show zip before city
	  In Europe zip is shown before the city. Enable this switch, if you need
	  this for the detail page

10. Records viewed per page

11. Custom tab (if empty, no custom fields are displayed
	  Name of the tab for custom fields and the headline for custom fields
	  in the detail page. Leave it blank, if you don't use custom fields.
	
12. Width of TEXTAREA fields
    Textarea field width can change depending on the CSS of your theme. 
	  Sometimes this results in a very bad layout of your form, so you can set
	  here the cols-setting of a textarea field.
	
13. Format for date entries
	  Select the format for date entry and display.
	
14. Format for numeric values
	  Select the format for numeric entry and display.
	
------------------------------------
Important note for Admins
------------------------------------
As an admin you should have access to all records of all
users in the Address Book. Therefore the "Show private contacts"
switch works different for the admin than for other users.
If it set to "on", all records you have entered are shown, if it's
set to "off" all records aof all users are shown.
A registered user will only see his own records marked as private.
So if you test the Address Book, create a user account and log in
as a normal user.

You can assign co-admins via the Zikula Permission system. If you want
to do this, create a new user permission, select a user and enter the
following valus:
- Component: AddressBook::
- Instance: ::
- Permissions Level: DELETE

----------------------------------------
Some special modes for the Address Book
----------------------------------------
1. Use as a contact list for your site
You should give guests and users only the "view" access .

2. Personal Address Book for each registered user
Check the option "Personal address book mode". Access rights are set
automatically (no rights for guests, all rights for users). 

This Address Book is available in many languages, but still many more
languages are welcome. Please feel free to send lang files and 
bugs/problems to me.

Thomas Smiatek
--------------
thomas@smiatek.com