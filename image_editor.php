<?php

declare(strict_types=1);

/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/

/**
 * wg Image Editor for xoops
 *
 * @copyright      xoops
 * @license        GPL 2.0 or later
 * @package        general
 * @author         Wedega - Email:<webmaster@wedega.com> - Website:<https://wedega.com>
 * @version        1.1 image_editor.php XOOPS Project (www.xoops.org) $
 */

use Xmf\Request;
use XoopsModules\Wgevents;
use XoopsModules\Wgevents\{
    Constants,
    Utility,
    Common\Resizer
};

include __DIR__ . '/header.php';
$GLOBALS['xoopsOption']['template_main'] = 'wgevents_image_editor.tpl';

require_once \XOOPS_ROOT_PATH . '/header.php';

$utility = new \XoopsModules\Wgevents\Utility();

$op         = Request::getString('op', 'list');
$sourceId   = Request::getInt('id', Request::getInt('imageIdCrop'));
$origin     = Request::getString('imageOrigin');
$start      = Request::getInt('start');
$limit      = Request::getInt('limit', $helper->getConfig('userpager'));
$img_resize = Request::getInt('img_resize');

$uid = $xoopsUser instanceof \XoopsUser ? $xoopsUser->id() : 0;

// get all objects/classes/vars needed for image editor
$imageClass = 0; //identifier for different image classes; currently not used
$imgCurrent = [];

//if ($imageClass === Constants::IMAGECLASS_MEMBER) {
    $imageId      = $sourceId;
    $imageHandler = $eventHandler;
    $imageObj     = $eventHandler->get($sourceId);
    $imageOrigin  = 'id';
    $imgName  = \mb_substr(\str_replace(' ', '', $imageObj->getVar('name')), 0, 20) . '.jpg';
    $imageDir = '/uploads/wgevents/events/logos/' . $imageObj->getVar('submitter') . '/';
    $imgPath  = \XOOPS_ROOT_PATH . $imageDir;
    $imgUrl   = \XOOPS_URL . $imageDir;
    $imgFinal = $imgPath . $imgName;
    $imgTemp  = \WGEVENTS_UPLOAD_PATH . '/temp/' . $imgName;
    $redir    = 'event.php?op=list&amp;start=' . $start . '&amp;limit=' . $limit;
    $nameObj  = 'title';
    $fieldObj = 'logo';
    $submObj  = 'submitter';
/*
} else {
    // only used if image editor is used for different source
    // TODO: maybe for categories
}
*/

$imgCurrent['img_name'] = $imageObj->getVar($fieldObj);
$imgCurrent['src'] = $imgUrl . $imageObj->getVar($fieldObj);
$imgCurrent['origin'] = $imageClass;
$images = [];

$image_array = \XoopsLists::getImgListAsArray($imgPath);
$i = 0;
foreach ($image_array as $image_img) {
    if ('blank.gif' !== $image_img) {
        $i++;
        $images[$i]['id'] = 'imageSelect'.$i;
        $images[$i]['name'] = $image_img;
        $images[$i]['title'] = $image_img;
        $images[$i]['origin'] = 0; //identifier for different image classes; currently not used
        if ($imgCurrent['img_name'] === $image_img) {
            $images[$i]['selected'] = 1;
        }
        $images[$i]['src'] = $imgUrl . $image_img;
    }
}
// var_dump($images);
$GLOBALS['xoopsTpl']->assign('images', $images);
unset($images);
// end: get all objects/classes/vars needed for image editor

// Define Stylesheet
$GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/assets/css/style.css');
$GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/assets/css/cropper/imageeditor.css');

// add scripts
//$GLOBALS['xoTheme']->addScript(\XOOPS_URL . '/modules/wgevents/assets/js/admin.js');

// assign vars
$GLOBALS['xoopsTpl']->assign('wgevents_url', \WGEVENTS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_icon_url_16', \WGEVENTS_ICONS_URL . '/16');
$GLOBALS['xoopsTpl']->assign('wgevents_icon_url_32', \WGEVENTS_ICONS_URL . '/32');
$GLOBALS['xoopsTpl']->assign('wgevents_upload_url', \WGEVENTS_UPLOAD_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_upload_path', \WGEVENTS_UPLOAD_PATH);
$GLOBALS['xoopsTpl']->assign('wgevents_image_editor', \WGEVENTS_URL);
$GLOBALS['xoopsTpl']->assign('wgevents_upload_image_url', $imgUrl);
$GLOBALS['xoopsTpl']->assign('gridtarget', $imgName);
$GLOBALS['xoopsTpl']->assign('imgCurrent', $imgCurrent);
$GLOBALS['xoopsTpl']->assign('imageId', $imageId);
$GLOBALS['xoopsTpl']->assign('imageOrigin', $imageOrigin);
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('limit', $limit);

// Breadcrumbs
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_INDEX, 'link' => 'index.php'];
$xoBreadcrumbs[] = ['title' => $imageObj->getVar('name'), 'link' => 'event.php?op=show&amp;id=' . $imageObj->getVar('id')];
$xoBreadcrumbs[] = ['title' => \_MA_WGEVENTS_IMG_EDITOR];

// get config for images
$maxwidth  = $helper->getConfig('maxwidth_image');
$maxheight = $helper->getConfig('maxheight_image');
$maxsize   = $helper->getConfig('maxsize_image');
$mimetypes = $helper->getConfig('mimetypes_image');

switch ($op) {

    case 'creategrid':
        // create an image grid based on given sources
        $type   = Request::getInt('type', 4);
        $src[1] = Request::getString('src1');
        $src[2] = Request::getString('src2');
        $src[3] = Request::getString('src3');
        $src[4] = Request::getString('src4');
        $src[5] = Request::getString('src5');
        $src[6] = Request::getString('src6');
        $target = Request::getString('target');

        $images = [];
        for ($i = 1; $i <= 6; $i++) {
            if ('' !== $src[$i]) {
                $file       = \str_replace(\XOOPS_URL, \XOOPS_ROOT_PATH, $src[$i]);
                $images[$i] = ['file' => $file, 'mimetype' => \mime_content_type($file)];
            }
        }

        // create basic image
        $tmp   = \imagecreatetruecolor($maxwidth, $maxheight);
        $imgBg = imagecolorallocate($tmp, 0, 0, 0);
        imagefilledrectangle($tmp, 0, 0, $maxwidth, $maxheight, $imgBg);

        $final = XOOPS_UPLOAD_PATH . '/wgevents/temp/' . $target;
        \unlink($final);
        \imagejpeg($tmp, $final);
        \imagedestroy($tmp);

        $imgTemp = XOOPS_UPLOAD_PATH . '/wgevents/temp/' . $uid . 'imgTemp';

        $imgHandler = new Resizer();
        if (4 === $type) {
            for ($i = 1; $i <= 4; $i++) {
                \unlink($imgTemp . $i . '.jpg');
                $imgHandler->sourceFile    = $images[$i]['file'];
                $imgHandler->endFile       = $imgTemp . $i . '.jpg';
                $imgHandler->imageMimetype = $images[$i]['mimetype'];
                $imgHandler->maxWidth      = (int)\round($maxwidth / 2 - 1);
                $imgHandler->maxHeight     = (int)\round($maxheight / 2 - 1);
                $imgHandler->jpgQuality    = 90;
                $imgHandler->resizeAndCrop();
            }
            $imgHandler->mergeType = 4;
            $imgHandler->endFile   = $final;
            $imgHandler->maxWidth  = $maxwidth;
            $imgHandler->maxHeight = $maxheight;
            for ($i = 1; $i <= 4; $i++) {
                $imgHandler->sourceFile = $imgTemp . $i . '.jpg';
                $imgHandler->mergePos   = $i;
                $imgHandler->mergeImage();
                \unlink($imgTemp . $i . '.jpg');
            }
        }
        if (6 === $type) {
            for ($i = 1; $i <= 6; $i++) {
                $imgHandler->sourceFile    = $images[$i]['file'];
                $imgHandler->endFile       = $imgTemp . $i . '.jpg';
                $imgHandler->imageMimetype = $images[$i]['mimetype'];
                $imgHandler->maxWidth      = (int)\round($maxwidth / 3 - 1);
                $imgHandler->maxHeight     = (int)\round($maxheight / 2 - 1);
                $imgHandler->resizeAndCrop();
            }
            $imgHandler->mergeType = 6;
            $imgHandler->endFile   = $final;
            $imgHandler->maxWidth  = $maxwidth;
            $imgHandler->maxHeight = $maxheight;
            for ($i = 1; $i <= 6; $i++) {
                $imgHandler->sourceFile = $imgTemp . $i . '.jpg';
                $imgHandler->mergePos   = $i;
                $imgHandler->mergeImage();
                \unlink($imgTemp . $i . '.jpg');
            }
        }
        break;

    case 'cropimage':
        // save base64_image and resize to maxwidth/maxheight
        $base64_image_content = Request::getString('croppedImage');
        if (\preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            \file_put_contents($imgTemp, base64_decode(\str_replace($result[1], '', $base64_image_content), true));
        }

        $imgHandler                = new Resizer();
        $imgHandler->sourceFile    = $imgTemp;
        $imgHandler->endFile       = $imgTemp;
        $imgHandler->imageMimetype = 'image/jpeg';
        $imgHandler->maxWidth      = $maxwidth;
        $imgHandler->maxHeight     = $maxheight;
        $ret                       = $imgHandler->resizeImage();

        //\unlink($imgFinal);
        break;
    case 'saveImageSelected':
        // save image selected from list of available images in upload folder
        // Set Vars
        $image_id = Request::getString('image_id');
        // remove '_image' from id
        $image_id = \substr($image_id, 0, -6);
        $imageObj->setVar($fieldObj, $image_id);
        //$imageObj->setVar($submObj, $uid); // do not change submitter of event
        // Insert Data
        if ($imageHandler->insert($imageObj)) {
            \redirect_header($redir, 2, _MA_WGEVENTS_FORM_OK);
        }
        $GLOBALS['xoopsTpl']->assign('error', $imageObj->getHtmlErrors());
        break;

    case 'saveGrid':
        // save before created grid image
        $imgTempGrid = Request::getString('gridImgFinal');
        $ret = \rename($imgTempGrid, $imgFinal);
        // Set Vars
        $imageObj->setVar($fieldObj, $imgName);
        //$imageObj->setVar($submObj, $uid); // do not change submitter of event
        // Insert Data
        if ($imageHandler->insert($imageObj)) {
            \redirect_header($redir, 2, _MA_WGEVENTS_FORM_OK);
        }
        $GLOBALS['xoopsTpl']->assign('error', $imageObj->getHtmlErrors());

        break;
    case 'saveCrop':
        // save before created cropped image
        \unlink($imgFinal);
        $ret = \rename($imgTemp, $imgFinal);
        // Set Vars
        $imageObj->setVar($fieldObj, $imgName);
        //$imageObj->setVar($submObj, $uid); // do not change submitter of event
        // Insert Data
        if ($imageHandler->insert($imageObj, true)) {
            \redirect_header($redir, 2, _MA_WGEVENTS_FORM_OK);
        }
        $GLOBALS['xoopsTpl']->assign('error', $imageObj->getHtmlErrors());

        break;
    case 'uploadImage':
        // Security Check
        if (!$GLOBALS['xoopsSecurity']->check()) {
            \redirect_header($redir, 3, \implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
        }
        // Set Vars
        require_once \XOOPS_ROOT_PATH . '/class/uploader.php';
        $fileName       = $_FILES['attachedfile']['name'];
        $imageMimetype  = $_FILES['attachedfile']['type'];
        $uploaderErrors = '';
        $uploader       = new \XoopsMediaUploader($imgPath, $mimetypes, $maxsize, null, null);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $extension = \preg_replace('/^.+\.([^.]+)$/sU', '', $fileName);
            $imgName   .= '.' . $extension;
            $uploader->setPrefix($imgName);
            $uploader->fetchMedia($_POST['xoops_upload_file'][0]);
            if (!$uploader->upload()) {
                $uploaderErrors = $uploader->getErrors();
            } else {
                $savedFilename = $uploader->getSavedFileName();
                $imageObj->setVar($fieldObj, $savedFilename);
                // resize image
                if (1 == $img_resize) {
                    $imgHandler                = new Resizer();
                    $imgHandler->sourceFile    = $imgPath . $savedFilename;
                    $imgHandler->endFile       = $imgPath . $savedFilename;
                    $imgHandler->imageMimetype = $imageMimetype;
                    $imgHandler->maxWidth      = $maxwidth;
                    $imgHandler->maxHeight     = $maxheight;
                    $result                    = $imgHandler->resizeImage();
                }

                $imageObj->setVar($fieldObj, $savedFilename);
                //$imageObj->setVar($submObj, $uid); // do not change submitter of event
            }
        } else {
            if ($fileName > '') {
                $uploaderErrors = $uploader->getErrors();
            }
        }
        if ('' !== $uploaderErrors) {
            \redirect_header($redir, 5, $uploaderErrors);
        }
        // Insert Data
        if ($imageHandler->insert($imageObj)) {
            \redirect_header($redir, 2, _MA_WGEVENTS_FORM_OK);
        }
        // Get Form
        $GLOBALS['xoopsTpl']->assign('error', $imageObj->getHtmlErrors());
        $form = $imageObj->getFormUploadImage($imageOrigin, $imageId);
        $GLOBALS['xoopsTpl']->assign('form', $form->render());
        break;

    case 'imghandler':
    default:
        $GLOBALS['xoTheme']->addStylesheet(\WGEVENTS_URL . '/assets/css/cropper/cropper.min.css');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/cropper/cropper.min.js');
        $GLOBALS['xoTheme']->addScript(\WGEVENTS_URL . '/assets/js/cropper/cropper-main.js');

        $GLOBALS['xoopsTpl']->assign('nbModals', [1, 2, 3, 4, 5, 6]);

        // get form for upload album image
        $currImage   = $imageObj->getVar($fieldObj);
        if ('' == $currImage) {
            $currImage = 'blank.gif';
        }
        $image_path = $imgPath . $currImage;
        $width = 0;
        $height= 0;
        if (\file_exists($image_path)) {
            // get size of current album image
            list($width, $height, $type, $attr) = \getimagesize($image_path);
        }
        $GLOBALS['xoopsTpl']->assign('image_path', $image_path);
        $GLOBALS['xoopsTpl']->assign('albimage_width', $width);
        $GLOBALS['xoopsTpl']->assign('albimage_height', $height);

        $form = getFormUploadImage($imageOrigin, $imageId);
        $GLOBALS['xoopsTpl']->assign('form_uploadimage', $form->render());

        $GLOBALS['xoopsTpl']->assign('btn_style', 'btn-default');

        break;
}

$GLOBALS['xoopsTpl']->assign('panel_type', $helper->getConfig('panel_type'));

include __DIR__ . '/footer.php';

/**
 * @public function getFormUploadImage:
 * provide form for uploading a new image
 * @param $imageOrigin
 * @param $imageId
 * @return \XoopsThemeForm
 */
function getFormUploadImage($imageOrigin, $imageId)
{
    $helper = \XoopsModules\Wgevents\Helper::getInstance();
    // Get Theme Form
    \xoops_load('XoopsFormLoader');
    $form = new \XoopsThemeForm('', 'formuploadimage', 'image_editor.php', 'post', true);
    $form->setExtra('enctype="multipart/form-data"');
    // upload new image
    $imageTray1      = new \XoopsFormElementTray(\_MA_WGEVENTS_FORM_UPLOAD_IMG, '<br>');
    $imageFileSelect = new \XoopsFormFile('', 'attachedfile', $helper->getConfig('maxsize'));
    $imageTray1->addElement($imageFileSelect);
    $form->addElement($imageTray1);

    $cond = \_MA_WGEVENTS_IMG_MAXSIZE . ': ' . ($helper->getConfig('maxsize_image') / 1048576) . ' ' . \_MA_WGEVENTS_SIZE_MB . '<br>';
    //$cond .= \_MI_WGEVENTS_MAXWIDTH . ': ' . $helper->getConfig('maxwidth') . ' px<br>';
    //$cond .= \_MI_WGEVENTS_MAXHEIGHT . ': ' . $helper->getConfig('maxheight') . ' px<br>';
    $cond .= \_MA_WGEVENTS_IMG_MIMETYPES . ': ' . \implode(', ', $helper->getConfig('mimetypes_image')) . '<br>';
    $form->addElement(new \XoopsFormLabel(\_MA_WGEVENTS_IMG_EDITOR_UPLOAD, $cond));

    $imageTray3      = new \XoopsFormElementTray(\_MA_WGEVENTS_IMG_EDITOR_RESIZE, '');
    $resizeinfo = \str_replace('%w', (string)$helper->getConfig('maxwidth_image'), \_MA_WGEVENTS_IMG_EDITOR_RESIZE_DESC);
    $resizeinfo = \str_replace('%h', (string)$helper->getConfig('maxheight_image'), $resizeinfo);
    $imageTray3->addElement(new \XoopsFormLabel($resizeinfo, ''));
    $imageTray3->addElement(new \XoopsFormRadioYN('', 'img_resize', 1));
    $form->addElement($imageTray3);

    $form->addElement(new \XoopsFormHidden($imageOrigin, $imageId));
    $form->addElement(new \XoopsFormHidden('op', 'uploadImage'));
    $form->addElement(new \XoopsFormButtonTray('', \_SUBMIT, 'submit', '', false));

    return $form;
}
