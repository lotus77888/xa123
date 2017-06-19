<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  | -------------------------------------------------------------------------
  | URI ROUTING
  | -------------------------------------------------------------------------
  | This file lets you re-map URI requests to specific controller functions.
  |
  | Typically there is a one-to-one relationship between a URL string
  | and its corresponding controller class/method. The segments in a
  | URL normally follow this pattern:
  |
  |	example.com/class/method/id/
  |
  | In some instances, however, you may want to remap this relationship
  | so that a different class/function is called than the one
  | corresponding to the URL.
  |
  | Please see the user guide for complete details:
  |
  |	https://codeigniter.com/user_guide/general/routing.html
  |
  | -------------------------------------------------------------------------
  | RESERVED ROUTES
  | -------------------------------------------------------------------------
  |
  | There are three reserved routes:
  |
  |	$route['default_controller'] = 'welcome';
  |
  | This route indicates which controller class should be loaded if the
  | URI contains no data. In the above example, the "welcome" class
  | would be loaded.
  |
  |	$route['404_override'] = 'errors/page_missing';
  |
  | This route will tell the Router which controller/method to use if those
  | provided in the URL cannot be matched to a valid route.
  |
  |	$route['translate_uri_dashes'] = FALSE;
  |
  | This is not exactly a route, but allows you to automatically route
  | controller and method names that contain dashes. '-' isn't a valid
  | class or method name character, so it requires translation.
  | When you set this option to TRUE, it will replace ALL dashes in the
  | controller and method URI segments.
  |
  | Examples:	my-controller/index	-> my_controller/index
  |		my-controller/my-method	-> my_controller/my_method
 */
$route['default_controller'] = 'welcome';
$route['404_override'] = '';

// Web Services Testing
$route['login'] = 'Admin/login';
$route['forgotPassword'] = 'Admin/forgotPwd';
$route['changePassword'] = 'Admin/changePassword';
$route['userData'] = 'Admin/toGetUserDataUsingResetKey';
$route['updatePassword'] = 'Admin/toUpdateUserPassword';
$route['updateUserProfile'] = 'Admin/toUpdateUserProfile';
$route['getUserData'] = 'Admin/toGetUserData';
$route['checkUserExistingPassword'] = 'Admin/chkUserExisitingPassword';

/* Dashboard Module */
$route['toGetAllAppData'] = 'Admin/toGetAllTablesCount';
/* end */

/* manage Admins Module */
$route['addAdmindata'] = 'ManageAdmin/toAddAdminData';
$route['getSubAdminsdata'] = 'ManageAdmin/toGetSubAdminsData';
$route['editSubAdmindata'] = 'ManageAdmin/toEditSubAdmin';
$route['updateSubAdmindata'] = 'ManageAdmin/toUpdateSubAdmin';
$route['deleteSubAdmindata/(:num)'] = 'ManageAdmin/toDeleteSubAdmins/$1';
$route['checkEmail'] = 'ManageAdmin/toCheckEmail';
/* end */

/* users Module */
$route['addNewUser'] = 'User/toAddNewUser';
$route['getAllUserData'] = 'User/toGetAllUsersData';
$route['editUserData'] = 'User/toEditUserData';
$route['updateUserData'] = 'User/toUpdateUser';
$route['deleteUserData/(:num)'] = 'User/toDeleteUser/$1';
$route['checkUserEmail'] = 'User/toCheckUserEmail';
/* end */

/* category module */
$route['addCategoryData'] = 'Category/toAddNewCategory';
$route['getHomeCategoryCount'] = 'Category/togetHomeCategoryCount';
$route['getCategroyData'] = 'Category/toGetCategoryData';
$route['getHomeCategroyData'] = 'Category/toGetHomeCategroyData';
$route['getSelectCategoryData'] = 'Category/toGetSelectCategoryData';
$route['editCategroyData'] = 'Category/toEditCategoryData';
$route['editCategroyDataBySlug'] = 'Category/toEditCategoryDataBySlug';
$route['editCategoryDataById'] = 'Category/toEditCategoryDataById';
$route['updateCategoryData'] = 'Category/toUpdateCategoryData';
$route['deleteCategoryData/(:num)'] = 'Category/toDeleteCategoryData/$1';
$route['checkCategory'] = 'Category/tocheckCategory';
$route['categoryDataByPostId'] = 'Category/toCategoryDataByPostId';
/* end */

/* Keyword module */
$route['addNewKeyword'] = 'Keyword/toAddNewKeyword';
$route['getKeywordData'] = 'Keyword/toGetKeywordData';
$route['editKeywordData'] = 'Keyword/toEditKeywordData';
$route['updateKeywordData'] = 'Keyword/toUpdateKeywordData';
$route['getSelectedKeywordData'] = 'Keyword/toGetSelectedKeywordData';
$route['deleteKeywordData/(:num)'] = 'Keyword/toDeleteKeywordData/$1';
/* end */

/* Media module */
$route['addNewMedia'] = 'Media/toAddNewMedia';
$route['getMediaData'] = 'Media/toGetMediaData';
$route['editMediaData'] = 'Media/toEditMediaData';
$route['updateMediaData'] = 'Media/toUpdateMediaData';
$route['deleteMediaData/(:num)'] = 'Media/toDeleteMediaData/$1';
/* end */

/* techexplorers Module */
$route['getAllTechexplorersData'] = 'Techexplorers/togetAllTechexplorersData';
$route['getAllTechexplorersDataByLimit'] = 'Techexplorers/togetAllTechexplorersDataByLimit';
$route['getAllXplorersDataByLimit'] = 'Techexplorers/toGetAllXplorersDataByLimit';
$route['getAllTechexplorersDataStatus'] = 'Techexplorers/togetAllTechexplorersDataStatus';
$route['getAllTechexplorersDataStatusByAsc'] = 'Techexplorers/togetAllTechexplorersDataStatusByAsc';
$route['addNewTechexplorerData'] = 'Techexplorers/toaddNewTechexplorerData';
$route['checkTechexplorerEmail'] = 'Techexplorers/tocheckTechexplorerEmail';
$route['editTechexplorerData'] = 'Techexplorers/toeditTechexplorerData';
$route['updateTechexplorerData'] = 'Techexplorers/toaddNewTechexplorerData';
$route['deleteTechexplorerData/(:num)'] = 'Techexplorers/todeleteTechexplorerData/$1';
$route['deleteTechexplorerData/(:num)'] = 'Techexplorers/todeleteTechexplorerData/$1';
/* end */


/* Post module */
$route['addNewPost'] = 'Post/toAddNewPost';
$route['getPostData'] = 'Post/toGetPostData';
$route['getSelectedPostData'] = 'Post/toGetSelectedPostData';
$route['getPostDataByCategory'] = 'Post/toGetPostDataByCategory';
$route['editPostData'] = 'Post/toEditPostData';
$route['updatePostData'] = 'Post/toAddNewPost';
$route['deletePostData/(:num)'] = 'Post/toDeletePostData/$1';
$route['deleteMultiplePostData'] = 'Post/toDeleteMultiplePostData';
$route['getImagesData'] = 'Post/index';
$route['toGetPostPublishData'] = 'Post/toGetPostPublishData';
$route['toGetPostPublishDataByCategory'] = 'Post/toGetPostPublishDataByCategory';
$route['toGetPostPublishDataByCategoryById'] = 'Post/toGetPostPublishDataByCategoryById';
$route['toGetPostPublishDataByCategoryCount'] = 'Post/toGetPostPublishDataByCategoryCount';
$route['toGetPostPublishDataByCategoryCountById'] = 'Post/toGetPostPublishDataByCategoryCountById';
$route['toGetPostPublishCount'] = 'Post/toGetPostPublishCount';
$route['getPostDetails'] = 'Post/getPostDetails';
$route['getSidebarDetails'] = 'Post/toGetSidebarDetails';
$route['getPreviewPostDetails'] = 'Post/toGetPreviewPostDetails';
$route['getTrendingPosts'] = 'Post/toGetTrendingPosts';
$route['getTrendingPostsByCategory'] = 'Post/toGetTrendingPostByCategory';
$route['getTrendingPostsByCategoryById'] = 'Post/getTrendingPostsByCategoryById';
$route['updatePostViewCount'] = 'Post/toUpdatePostViewById';
$route['checkPostTitle'] = 'Post/toCheckPostTitle';
$route['checkPostTitleForMobile'] = 'Post/toCheckPostTitleForMobile';
$route['checkPostSlug'] = 'Post/tocheckPostSlug';
$route['getPostSearchCount'] = 'Post/toGetPostSearchCount';
/* end */

/* Shows module */
$route['addNewShow'] = 'Shows/toAddNewShow';
$route['getShowsData'] = 'Shows/toGetShowsData';
$route['editShowsData'] = 'Shows/toEditShowsData';
$route['updateShowsData'] = 'Shows/toUpdateExistingShow';
$route['updateOrAddWatchLinkUpload'] = 'Shows/toUpdateOrAddWatchLinkUpload';
$route['deleteShowsData/(:num)'] = 'Shows/toDeleteShowsData/$1';
$route['toGetShowPublishData'] = 'Shows/toGetShowPublishData';
$route['toGetShowPublishCount'] = 'Shows/toGetShowPublishCount';
$route['toGetXplorerDataById'] = 'Shows/toGetXplorerDataById';
$route['toGetOnlyShowsData'] = 'Shows/toGetOnlyShowsData';
$route['toGetShowLinksData'] = 'Shows/toGetShowWatchLinksDataByShowId';
$route['toGetEpisodeData'] = 'Shows/toGetEpisodeData';
$route['getEpisodeDataCount'] = 'Shows/toGetEpisodeDataCount';
$route['togetSeasonsBasedOnShowId'] = 'Shows/togetSeasonsBasedOnShowId';
$route['deleteShowsWatchLinksData/(:num)'] = 'Shows/toDeleteShowsWatchLinksData/$1';
$route['checkShowSlug'] = 'Shows/toCheckShowSlug';
$route['checkShowName'] = 'Shows/toCheckShowName';
/* end */

/* Episode module */
$route['addNewEpisode'] = 'Episode/toaddNewEpisode';
$route['getEpisodeData'] = 'Episode/toGetEpisodeData';
$route['getSelectedShowData'] = 'Episode/toGetSelectedShowData';
$route['editEpisodeData'] = 'Episode/toeditEpisodeData';
$route['updateEpisodeData'] = 'Episode/toaddNewEpisode';
$route['getSeasonsByShow'] = 'Episode/togetSeasonsByShow';
$route['getWatchLinksByShow'] = 'Episode/togetWatchLinksByShow';
$route['getShowSeasonsByEpisodeId'] = 'Episode/togetShowSeasonsByEpisodeId';
$route['deleteEpisodeData/(:num)'] = 'Episode/toDeleteEpisodeData/$1';
$route['checkEpisodeSlug'] = 'Episode/toCheckEpisodeSlug';
$route['getEpisodeDetails'] = 'Episode/toGetEpisodeDetails';
$route['getTrendingEpisodes'] = 'Episode/togetTrendingEpisodes';
$route['getRelatedEpisodeDetails'] = 'Episode/toGetRelatedEpisodeDetails';
$route['getNextEpisodeDetails'] = 'Episode/toGetNextEpisodeDetails';
$route['toGetXplorerDataBySeasonId'] = 'Episode/toGetXplorerDataBySeasonId';
$route['updateEpisodeViewCount'] = 'Episode/toUpdateEpisodeViewCount';
/* end */

/* Page module */
$route['getPageData'] = 'Page/toGetPageData';
$route['getHomePagesData'] = 'Page/toGetHomePagesData';
$route['getSelectOptionData'] = 'Page/toGetSelectOptionData';
$route['getPagesDataBySlug'] = 'Page/toGetPagesDataBySlug';
$route['addNewPage'] = 'Page/toAddNewPage';
$route['editPageData'] = 'Page/toEditPageData';
$route['updatePage'] = 'Page/toAddNewPage';
$route['deletePageData/(:num)'] = 'Page/toDeletePageData/$1';
$route['checkPageTitle'] = 'Page/toCheckPageTitle';
$route['checkPageTitleForMobile'] = 'Page/toCheckPageTitleForMobile';
$route['checkPageSlug'] = 'Page/tocheckPageSlug';
$route['getPageSidebarDetails'] = 'Page/toGetPageSidebarDetails';
/* end */

/* Settings Module */
$route['editSettingsData'] = 'Settings/toEditSettingsData';
$route['updateSettings'] = 'Settings/toUpdateSettings';
/* end */


/* Contacts Module */
$route['contactsData'] = 'Contacts/toGetContactsData';
$route['SendContactUsData'] = 'Contacts/toSendContactUsData';
/* end */

/* NewsLetter Module */
$route['newsletterData'] = 'Newsletter/tonewsletterData';
$route['addnewsletter'] = 'Newsletter/addnewsletter';
$route['checkNewletterEmail'] = 'Newsletter/toCheckNewletterEmail';
$route['addnewsletterdata'] = 'Newsletter/toAddnewsletterdata';
$route['editNewsletterdata'] = 'Newsletter/toeditNewsletterdata';
$route['deleteNewsletterdata/(:num)'] = 'Newsletter/todeleteNewsletterdata/$1';
$route['deleteMultipleNLData'] = 'Newsletter/toDeleteMultipleNLData';
$route['getSearchCount'] = 'Newsletter/toGetSearchCount';
/* end */


/* Sidebars */
$route['sidebarsData'] = 'Sidebar/tosidebarsData';
$route['sidebarsActiveData'] = 'Sidebar/tosidebarsActiveData';
$route['addSidebar'] = 'Sidebar/toaddSidebar';
$route['deleteSidebarData/(:num)'] = 'Sidebar/toDeleteSidebarData/$1';
$route['editSidebardata'] = 'Sidebar/toEditSidebardata';
$route['checkSidebarTitle'] = 'Sidebar/toCheckSidebarTitle';
/* end */
/* Rss feed Module */
$route['xploration-rssfeed'] = 'Rssfeed/index';
/* end */

/* contest */
$route['contestContactInfo'] = 'Newsletter/toContestContactInfo';
/* end */
$route['translate_uri_dashes'] = FALSE;
