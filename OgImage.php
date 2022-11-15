<?php

namespace NYCO;

class OgImage {
  public $header = 'Content-Type: image/png';

  public $w = 1200;

  public $h = 628;

  public $grid = 8;

  public $margin = 10; // multiplier for margin; $grid * $margin

  public $color = array(18, 47, 90); // Scale default 4 (light mode)

  public $verticalAlign = 'top';

  public $fontRegular = __DIR__ . '/assets/PublicSans-Regular.ttf';

  public $fontBold = __DIR__ . '/assets/PublicSans-Bold.ttf';

  public $fontSizeTitle = 34;

  public $fontSizeSubtitle = 16;

  public $wrapTitle = 28;

  public $wrapSubtitle = 58;

  public $preText = '';

  public $title = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor';

  public $subtitleBold = 'Incididunt ut labore et dolore';

  public $subtitle = 'Magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris';

  public $backgroundImg = __DIR__ . '/assets/background.png';

  public $logoImg = __DIR__ . '/assets/logo.png';

  /**
   * Construct the instance
   *
   * @return  Object  Instance of OgImage
   */
  public function __construct() {
    return $this;
  }

  /**
   * Create the image by composing layers and adding text.
   *
   * @return  Object  Instance of OgImage
   */
  public function create() {
    /**
     * Background
     */

    $this->background = imagecreatefrompng($this->backgroundImg);

    /**
     * Logo
     */

    $this->logo = imagecreatefrompng($this->logoImg);

    imagealphablending($this->logo, true);

    imagesavealpha($this->logo, true);

    /**
     * Compose background and logo layers
     */

    $this->image = imagecreatetruecolor($this->w, $this->h);

    imagecopy($this->image, $this->background, 0, 0, 0, 0, $this->w, $this->h);

    imagecopy($this->image, $this->logo, 0, 0, 0, 0, $this->w, $this->h);

    /**
     * Text
     */

    $this->text = imagecreatetruecolor($this->w, $this->h);

    $color = imagecolorallocate($this->text, $this->color[0], $this->color[1], $this->color[2]);

    /**
     * Pre-title Text
     */

    $this->preText = wordwrap($this->preText, $this->wrapSubtitle, "\n");

    $boundsPreText = imagettfbbox($this->fontSizeSubtitle, 0, $this->fontBold, $this->preText);

    $preTextHeight = $boundsPreText[3] - $boundsPreText[5];

    /**
     * Title
     */

    $this->title = wordwrap($this->title, $this->wrapTitle, "\n");

    $boundsTitle = imagettfbbox($this->fontSizeTitle, 0, $this->fontBold, $this->title);

    $titleHeight = $boundsTitle[3] - $boundsTitle[5];

    /**
     * Subtitle (bold)
     */

    $this->subtitleBold = wordwrap($this->subtitleBold, $this->wrapSubtitle, "\n");

    $boundsSubtitleBold = imagettfbbox($this->fontSizeSubtitle, 0, $this->fontBold, $this->subtitleBold);

    $subtitleBoldHeight = $boundsSubtitleBold[3] - $boundsSubtitleBold[5];

    /**
     * Subtitle (regular)
     */

    $this->subtitle = wordwrap($this->subtitle, $this->wrapSubtitle, "\n");

    $boundsSubtitle = imagettfbbox($this->fontSizeSubtitle, 0, $this->fontRegular, $this->subtitle);

    $subtitleHeight = $boundsSubtitle[3] - $boundsSubtitle[5];

    /**
     * Alignment
     */

    $margin = $this->grid * $this->margin;

    $startTop = $margin + $this->fontSizeTitle + ($this->grid * 2);

    $startMiddle = ($this->h / 2) - (($titleHeight + $subtitleBoldHeight + $subtitleHeight) / 2);

    $startBottom = $this->h - $margin - $titleHeight - $subtitleBoldHeight - $subtitleHeight ;

    /**
     * Add Text to Image
     */

    if ('top' != $this->verticalAlign) {
      $startPreText = $margin + $this->fontSizeSubtitle;

      imagettftext($this->image, $this->fontSizeSubtitle, 0, $margin, $startPreText, $color, $this->fontBold, $this->preText);
    }

    if ('top' === $this->verticalAlign) {
      $titleY = $startTop;
    }

    if ('middle' === $this->verticalAlign) {
      $titleY = $startMiddle;
    }

    if ('bottom' === $this->verticalAlign) {
      $titleY = $startBottom;
    }

    imagettftext($this->image, $this->fontSizeTitle, 0, $margin, $titleY, $color, $this->fontBold, $this->title);

    $subtitleBoldY = $titleY + $titleHeight + $this->grid; // add previous y position to the previous height with a little spacing

    imagettftext($this->image, $this->fontSizeSubtitle, 0, $margin, $subtitleBoldY, $color, $this->fontBold, $this->subtitleBold);

    $subtitleY = $subtitleBoldY + $subtitleBoldHeight + $this->grid * 1.5;

    imagettftext($this->image, $this->fontSizeSubtitle, 0, $margin, $subtitleY, $color, $this->fontRegular, $this->subtitle);

    // Return the instance containing the image

    return $this;
  }

  /**
   * Destroy instance images
   *
   * @return  Object  Instance of OgImage
   */
  public function destroy() {
    imagedestroy($this->background);
    imagedestroy($this->logo);
    imagedestroy($this->text);
    imagedestroy($this->image);

    return $this;
  }
}
