<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

use App\Models\Role;
use App\Models\User;
use App\Models\EmailLogs;
use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\EmailTemplate;
use App\Models\NotificationMessage;
use App\Models\ShortCode;
use App\Models\Menu;
use App\Models\SubMenu;
// use Spatie\Permission\Models\Permission;
use App\Models\Permission;
use App\Models\AssignPermission;
use App\Models\ConnectPeople;
use App\Models\ConversationDeleteMessage;
use App\Models\GeneralTitle;
use App\Models\Goal;
use App\Models\GoalItem;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use App\Models\IndustryVerticalItem;
use App\Models\Message;
use App\Models\MessageStatus;
use App\Models\ReadMessage;
use App\Models\ProfRoleType;
use App\Models\ProRoleTypeItem;
use App\Models\UserCareerStatusPosition;
use App\Models\UserEducationalInformation;
use App\Models\UserGoalItem;
use App\Models\UserIndustyVerticalItem;
use App\Models\UserMessageReport;
use App\Models\UserProRoleTypeItem;
use App\Models\UserSpecialty;
use App\Models\MessageAsset;
use App\Models\TermsAndPrivacyPolicy;
use App\Models\Setting;
use App\Models\SeedStore;
use App\Models\ConnectionBookMark;
use App\Models\DocumentAsset;
use App\Models\GeneralTag;
use App\Models\Pitch;
use App\Models\PitchTag;
use App\Models\UserRefuseConnection;
use App\Models\PitchesBookMark;
use App\Models\PitcheReply;
use App\Models\PitchShare;
use App\Models\PitchContribution;
use App\Models\UserSeenPitch;

use DB;
use Validator;
use Auth;
use Session;
use Carbon;
use Image;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $RoleObj;
    public $UserObj;
    public $EmailObj;
    public $NotificationObj;
    public $FcnTokenObj;
    public $EmailTemplateObj;
    public $NotificationMessageObj;
    public $EmailShortCodeObj;
    public $MenuObj;
    public $SubMenuObj;
    public $PermissionObj;
    public $AssignPermissionObj;
    public $ContactObj;
    public $GeneralObj;
    public $UserCareerStatusObj;
    public $ProRoleTypeObj;
    public $ProRoleTypeItemObj;
    public $UserProRolteItemObj;
    public $GoalObj;
    public $GoalItemObj;
    public $UserGoalItemObj;
    public $GroupObj;
    public $GroupMemberObj;
    public $GroupMessageObj;
    public $MessageObj;
    public $MessageAssetObj;
    public $ReadMessageObj;
    public $MessageStatusObj;
    public $UserEducationalInfoObj;
    public $UserSpecialtyObj;
    public $IndustryVerticalItemObj;
    public $UserindustryVerticalItemObj;
    public $ConnectPeopleObj;
    public $ConversationDeleteMessageObj;
    public $TermsAndPrivacyPolicyObj;
    public $SettingObj;
    public $SeedStoreObj;
    public $ConnectionBookMarkObj;

    public $DocumentAssetObj;
    public $GeneralTagObj;
    public $PitchObj;
    public $PitchTagObj;
    public $UserRefuseConnectionObj;
    public $PitchesBookMarkObj;
    public $PitcheReplyObj;
    public $PitchShareObj;
    public $PitchContributionObj;
    public $UserSeenPitchObj;




    public function __construct() {

        $this->RoleObj = new Role();
        $this->UserObj = new User();
        $this->EmailObj = new EmailLogs();
        $this->FcnTokenObj = new FcmToken();
        $this->NotificationObj = new Notification();
        $this->NotificationMessageObj = new NotificationMessage();
        $this->EmailTemplateObj = new EmailTemplate();
        $this->EmailShortCodeObj = new ShortCode();
        $this->MenuObj = new Menu();
        $this->SubMenuObj = new SubMenu();
        $this->PermissionObj = new Permission();
        $this->AssignPermissionObj = new AssignPermission();
        $this->GeneralObj = new GeneralTitle();
        $this->UserCareerStatusObj = new UserCareerStatusPosition();
        $this->ProRoleTypeObj = new ProfRoleType();
        $this->ProRoleTypeItemObj = new ProRoleTypeItem();
        $this->UserProRolteItemObj = new UserProRoleTypeItem();
        $this->GoalObj = new Goal();
        $this->GoalItemObj = new GoalItem();
        $this->UserGoalItemObj = new UserGoalItem();
        $this->GroupObj = new Group();
        $this->GroupMemberObj = new GroupMember();
        $this->GroupMessageObj = new GroupMessage();
        $this->MessageObj = new Message();
        $this->MessageAssetObj = new MessageAsset();
        $this->ReadMessageObj = new ReadMessage();
        $this->MessageStatusObj = new MessageStatus();
        $this->UserEducationalInfoObj = new UserEducationalInformation();
        $this->UserSpecialtyObj = new UserSpecialty();
        $this->UserindustryVerticalItemObj = new UserIndustyVerticalItem();
        $this->IndustryVerticalItemObj = new IndustryVerticalItem();
        $this->ConnectPeopleObj = new ConnectPeople();
        $this->ConversationDeleteMessageObj = new ConversationDeleteMessage();
        $this->TermsAndPrivacyPolicyObj = new TermsAndPrivacyPolicy();
        $this->SettingObj = new Setting();
        $this->SeedStoreObj = new SeedStore();
        $this->ConnectionBookMarkObj = new ConnectionBookMark();
        $this->GeneralTagObj = new GeneralTag();
        $this->PitchObj = new Pitch();
        $this->PitchTagObj = new PitchTag();
        $this->DocumentAssetObj = new DocumentAsset();
        $this->UserRefuseConnectionObj = new UserRefuseConnection();
        $this->PitchesBookMarkObj = new PitchesBookMark();
        $this->PitcheReplyObj = new PitcheReply();
        $this->PitchShareObj = new PitchShare();
        $this->PitchContributionObj = new PitchContribution();
        $this->UserSeenPitchObj = new UserSeenPitch();
    }

    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendResponse($result = array(), $message, $count = 0)
    {
    	$response = [
            'success' => true,
            'records'    => $result,
            'message' => $message,
            'count'    => $count,
        ];
        return response()->json($response, 200);
    }


    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = array(), $code = 404)
    {
    	$response = [
            'success' => false,
            'message' => $error,
        ];

        if(!empty($errorMessages)){
            $response['records'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
