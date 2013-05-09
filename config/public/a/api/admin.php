<?php

require_once(__DIR__.'/api.php');

class AdminApi extends Api {
  public static function get($req) {
    $req = req($req, array('menu','fields'));

    switch ($req->fields) {
      case '/campaigns/new':
        return self::menu('Create a new campaign',
          self::field('Partner', 'partner', 'partner', array(
            'placeholder' => 'Choose a partner...',
            'required' => TRUE
          )),
          self::field('Campaign name', 'title', 'title', array(
            'placeholder' => 'Name of the campaign',
            'required' => TRUE
          )),
          self::field('Campaign tag', 'name', array(
            'placeholder' => '(campaign slug)',
            'lowercase' => TRUE
          )),
          self::field('Start date', 'start_date', 'date', array(
            'placeholder' => 'launch date'
            // default => NOW
          )),
          self::field('Goal', 'goal', 'money', array(
            'placeholder' => '$ to raise'
          ))
        );

      case '/partners/new':
        return self::menu('Create a new partner',
          self::field('Name', 'name', array(
            'width' => 500,
            'required' => TRUE,
            'placeholder' => 'name of the organization'
          )),
          self::field('Site name', 'domain', array(
            'after' => '.' . $_SERVER["HTTP_HOST"],
            'lowercase' => TRUE,
            'placeholder' => 'subdomain name'
          )),
          self::field('Location', 'location', array(
            'lowercase' => TRUE,
            'propercase' => TRUE,
            'placeholder' => 'city/state/country'
          )),
          self::field('Description', 'description', 'text', array(
            'placeholder' => 'what does this organization do?',
            'default' => 'A SeeYourImpact.org partner'
          ))
        );

      case '/teams/new':
        return self::menu('Create a new team',
          self::field('Campaign', 'campaign'),
          self::field('Team name', 'team_title', array(
            'propercase' => TRUE
          )),
          self::field('Goal', 'goal', 'money', array(
            'after' => ' (optional)'
          ))
        );

      case '/gifts/new':
        return self::menu('Create a new gift',
          self::field('Partner', 'partner', 'partner', array(
            'readonly' => TRUE
          )),
          self::field('Gift name', 'gift_name', array(
            'placeholder' => 'ex. tuition for a student'
          )),
          self::field('Price', 'price', 'money', array(
            'placeholder' => 'Price of full gift'
          ))
        );

      case '/fundraisers/new':
        return self::menu('Create a new fundraiser',
          self::field('Campaign', 'campaign', 'campaign', array(
            'required' => TRUE
          )),
          self::field('Team', 'team', 'team', array(
            'placeholder' => 'choose a team...',
            'other' => 'other...'
          )),
          self::field('Goal', 'goal', 'money', array(
            'placeholder' => '$ to raise'
          )),
          self::menu('Contact',
            self::field('Email', 'new_user.email', 'email', array(
              'placeholder' => 'e-mail address'
            )),
            self::field('First name', 'new_user.first', 'first', array(
              'placeholder' => 'first name'
            )),
            self::field('Last name', 'new_user.last', 'last', array(
              'placeholder' => 'last name'
            ))
          )
        );
    }

    switch ($req->menu) {
      case 'admin':
        return self::menu('Administration',
          self::getPartnerMenu(),
          self::getCampaignMenu(),
          self::getFundraisersMenu(),
          self::getDonorMenu(),
          self::getDonationMenu(),
          self::getGiftMenu(),
          self::getReportMenu()
        );
    }

    return NULL;
  }

  public static function getActions($req) {
    $req = req($req, array('menu','fields'));
    global $ADMIN_PARTNER;

    switch ($req->menu) {
      case 'admin':
        $home = empty($ADMIN_PARTNER) ? '/' : "/partner/$ADMIN_PARTNER";
        return array('home' => self::action('Home', $home));
    }
  }

  public static function bookmarks($type) {
    $bookmarks = array('Bookmarked');

    // TODO: fetch from database
    switch ($type) {
      case 'partner':
        $bookmarks[] = self::ref('PPES', '/partner/ppes');
        $bookmarks[] = self::ref('Chances for Children', '/partner/c4c');
        break;

      case 'campaign':
        $bookmarks[] = self::ref('Hawthorne', '/campaign/hawthorne');
        $bookmarks[] = self::ref('MSAF', '/campaign/give-big');
        $bookmarks[] = self::ref('Pratham Readathon', '/campaign/readathon');
        break;

      case 'donor':
        $bookmarks[] = self::ref('Steve Eisner', '/donor/24');
        $bookmarks[] = self::ref('Digvijay Chauhan', '/donor/8');
        $bookmarks[] = self::ref('Scott Oki', '/donor/9');
        break;

      default:
        return;
    }

    if (count($bookmarks) <= 1)
      return NULL;

    return call_user_func_array(array('self', 'menu'), $bookmarks);
  }

  public static function getPartnerMenu() {
    return self::menu('Partners',
      self::ref('All partners', '/partners'),
      self::bookmarks('partner'),
      self::ref('New partner', '/partners/new', '/partners')
    );
  }

  public static function getCampaignMenu() {
    // TODO: scope to my managed sites

    return self::menu('Campaigns',
      self::ref('All campaigns', '/campaigns', TRUE),
      self::bookmarks('campaign'),
      self::ref('New campaign', '/campaigns/new', TRUE)
    );
  }

  public static function getDonorMenu() {
    // TODO: scope to my managed sites

    return self::menu('Donors',
      self::ref('All donors', '/donors', '/partners'),
      self::bookmarks('donor')
    );
  }

  public static function getDonationMenu() { 
    // TODO: scope to my managed sites?

    return self::menu('Donations',
      self::ref('All donations', '/donations', '/partners')
    );
  }

  public static function getFundraisersMenu() { 
    // TODO: scope to my managed sites?

    return self::menu('Fundraisers',
      self::ref('All fundraisers', '/fundraisers', TRUE)
    );
  }
  
  public static function getGiftMenu() {
    // TODO: scope to my managed sites?

    return self::menu('Gifts',
      self::ref('All gifts', '/gifts', TRUE)
    );
  }
  
  public static function getReportMenu() {
    // TODO: scope to my managed sites?

    return self::menu('Reports',
      self::ref('All reports', '/reports', '/reports'),
      self::ref('Donation Report', '/activity', '/reports')
    );
  }
  

}

// Direct request = run the API
register_api(__FILE__, 'AdminApi');

