<?php
    /*********************************************************************************
     * Zurmo is a customer relationship management program developed by
     * Zurmo, Inc. Copyright (C) 2014 Zurmo Inc.
     *
     * Zurmo is free software; you can redistribute it and/or modify it under
     * the terms of the GNU Affero General Public License version 3 as published by the
     * Free Software Foundation with the addition of the following permission added
     * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
     * IN WHICH THE COPYRIGHT IS OWNED BY ZURMO, ZURMO DISCLAIMS THE WARRANTY
     * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
     *
     * Zurmo is distributed in the hope that it will be useful, but WITHOUT
     * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
     * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
     * details.
     *
     * You should have received a copy of the GNU Affero General Public License along with
     * this program; if not, see http://www.gnu.org/licenses or write to the Free
     * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
     * 02110-1301 USA.
     *
     * You can contact Zurmo, Inc. with a mailing address at 27 North Wacker Drive
     * Suite 370 Chicago, IL 60606. or at email address contact@zurmo.com.
     *
     * The interactive user interfaces in original and modified versions
     * of this program must display Appropriate Legal Notices, as required under
     * Section 5 of the GNU Affero General Public License version 3.
     *
     * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
     * these Appropriate Legal Notices must retain the display of the Zurmo
     * logo and Zurmo copyright notice. If the display of the logo is not reasonably
     * feasible for technical reasons, the Appropriate Legal Notices must display the words
     * "Copyright Zurmo Inc. 2014. All rights reserved".
     ********************************************************************************/

    /**
     * Used to test an issue with securityOptimization as false that was causing a failure in the walkthrough.
     */
    class ConversationPermissionTest extends ZurmoBaseTest
    {
        public static function setUpBeforeClass()
        {
            parent::setUpBeforeClass();
            SecurityTestHelper::createSuperAdmin();
            $super = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
        }

        public function testAddParicipantAndHaveParticipantRemoveSelf()
        {
            return; //Turn on once issue is fixed with SECURITY_OPTIMIZED and this bug.
            $super                      = User::getByUsername('super');
            Yii::app()->user->userModel = $super;
            $fileModel                  = ZurmoTestHelper::createFileModel();
            $accounts                   = Account::getByName('anAccount');
            $steven                     = UserTestHelper::createBasicUser('steven');

            $conversation              = new Conversation();
            $conversation->owner       = $super;
            $conversation->subject     = 'My test subject';
            $conversation->description = 'My test description';
            $this->assertTrue($conversation->save());

            $sally                      = UserTestHelper::createBasicUser('sally');

            $conversation->addPermissions($sally, Permission::READ_WRITE_CHANGE_PERMISSIONS_CHANGE_OWNER);
            $conversation->save();

            //Log in as sally, and remove her permission
            Yii::app()->user->userModel = $sally;
            //Breaks because SecurableItem 2 spots using SECURITY_OPTIMIZATION == false, think it is the first spot
            //todo: fix.
            $conversation->removePermissions(Yii::app()->user->userModel,
                                  Permission::READ_WRITE_CHANGE_PERMISSIONS_CHANGE_OWNER, Permission::ALLOW);
        }
    }
?>