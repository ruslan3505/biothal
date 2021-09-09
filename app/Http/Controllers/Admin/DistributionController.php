<?php

namespace App\Http\Controllers\Admin;

use App\Models\PhoneGroup;
use App\Http\Requests\Admin\Distribution\{
    Create as CreateEmailDistributionRequest,
    Update as UpdateEmailDistributionRequest,
    CreatePhone as CreatePhoneDistributionRequest,
    UpdatePhone as UpdatePhoneDistributionRequest,
    CreateGroup as CreateGroupDistributionRequest,
    UpdateGroup as UpdateGroupDistributionRequest,
};
use App\Http\Controllers\Controller;
use App\Models\EmailGroup;
use Illuminate\Http\Request;
use App\Models\EmailForEmailNewsletter;
use App\Models\PhoneReceive;
use DataTables;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\DefaultMail;
use App\Models\DistributionOffer;

class DistributionController extends Controller
{
    public function email(Request $request)
    {
        $emails = EmailForEmailNewsletter::where('is_receive', false)->with('group')->get();
        $groups = EmailGroup::all();
        if (empty($emails)) {
            return view('admin.distribution.email', [
                'emails',
                'groups'
            ]);
        }

        if ($request->ajax()) {
            foreach ($emails as $key => $value){
                $emails[$key]['number'] = $key+1;
            }
            return Datatables::of($emails)
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('group', function ($row) {
                    return !empty($row->group_id) ? $row->group->name :  'Без Группы';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" data-toggle="modal" data-target="#change_emails" id="' . ("emails_change" . $row->id) .'" data-id="' . $row->id . '" data-email="' . $row->email . '" data-group_id="' . $row->group_id . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $emails = EmailForEmailNewsletter::all();

        return view('admin.distribution.email', [
            'emails' => $emails,
            'groups' => $groups
        ]);
    }

    public function addEmail(CreateEmailDistributionRequest $request)
    {
        EmailForEmailNewsletter::create([
            'email' => $request->email,
            'group_id' => $request->group_id === '0' ? null : $request->group_id,
            'is_receive' => false
        ]);

        return response()->json([
            'message' => 'Эмейл успешно создан'
        ], 200);
    }

    public function editEmail(UpdateEmailDistributionRequest $request)
    {
        $email = EmailForEmailNewsletter::find($request->id);
        if(empty($email)){
            return response()->json([
                'message' => 'Эмейл не найден'
            ], 404);
        }
        $email->email = $request->email;
        $email->group_id = $request->group_id === '0' ? null : $request->group_id;
        $email->save();

        return response()->json([
            'message' => 'Эмейл успешно обновлен'
        ], 200);
    }

    public function deleteEmail(Request $request)
    {
        $status = $request->status;

        if ($status == 0) {
            if ($request->checked != 0) {

                foreach ($request->checked as $catId) {
                    $email = EmailForEmailNewsletter::where('id', (int)$catId)->delete();
                }
                return response()->json([
                    'accepted' => 'Эмейлы успешно удалены'
                ]);
            }

            return response()->json([
                'error' => "Выберите хотя бы 1 эмейл"
            ]);
        }

        if ($status == 1) {
            if ($request->checked != 0) {
                $values = [];
                foreach ($request->checked as $catId) {
                    $email = EmailForEmailNewsletter::where('id', (int)$catId)->first();

                    if ($email != null) {

                        $email->delete();
                    }
                }

                if (count(EmailForEmailNewsletter::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                return response()->json([
                    'accepted' => 'Эмейлы успешно удалены',
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'error' => "Выберите хотя бы 1 эмейл"
                ]);
            }
        }
    }

    public function addEmailGroup(CreateGroupDistributionRequest $request)
    {
        EmailGroup::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Группа успешно создана'
        ], 200);
    }

    public function editEmailGroup(UpdateGroupDistributionRequest $request)
    {
        $group = EmailGroup::find($request->id);
        if(empty($group)){
            return response()->json([
                'message' => 'Группа не найден'
            ], 404);
        }
        $group->name = $request->name;
        $group->save();

        return response()->json([
            'message' => 'Группа успешно обновлена'
        ], 200);
    }

    public function deleteEmailGroup(Request $request)
    {
        $status = $request->status;

        if ($status == 0) {
            if ($request->checked != 0) {

                foreach ($request->checked as $catId) {
                    $group = EmailGroup::where('id', (int)$catId)->delete();
                }
                return response()->json([
                    'accepted' => 'Группы успешно удалены'
                ]);
            }

            return response()->json([
                'error' => "Выберите хотя бы 1 группу"
            ]);
        }

        if ($status == 1) {
            if ($request->checked != 0) {
                $values = [];
                foreach ($request->checked as $catId) {
                    $group = EmailGroup::where('id', (int)$catId)->first();

                    if ($group != null) {

                        $group->delete();
                    }
                }

                if (count(EmailGroup::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                return response()->json([
                    'accepted' => 'Группы успешно удалены',
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'error' => "Выберите хотя бы 1 группу"
                ]);
            }
        }
    }

    public function sendEmails(Request $request)
    {
        $groupId = $request->group_id === '0' ? null : $request->group_id;
        $emails = EmailForEmailNewsletter::where('group_id', $groupId)->get();

        $count = 0;
        if(!empty($emails)){
            foreach($emails as $email){
                if(!empty($request->description)){
                    $count += 1;
                    Mail::to($email->email)->send(new DefaultMail($request->description));
                } else {
                    return response()->json([
                        'message' => 'Вы не можете отправить письмо с пустым текстом',
                    ], 404);
                }
            }
        } else {
            return response()->json([
                'message' => 'Не найдено ни одного эмейла в группе',
            ], 404);
        }
        if($count === 0){
            return response()->json([
                'message' =>  'Писем не отправлено',
            ], 200);
        }
        return response()->json([
            'message' =>  $count > 1 ? "Вы успешно отправили ".$count." писем" : "Вы успешно отправили ".$count." письмо" .'',
        ], 200);
    }

    public function phone(Request $request)
    {
        $phones = PhoneReceive::where('is_receive', true)->with('group')->get();
        if (empty($phones)) {
            return view('admin.distribution.phone', [
                'phones'
            ]);
        }

        if ($request->ajax()) {
            foreach ($phones as $key => $value){
                $phones[$key]['number'] = $key+1;
            }
            return Datatables::of($phones)
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('group', function ($row) {
                    return !empty($row->group_id) ? $row->group->name :  'Без Группы';
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" data-toggle="modal" data-target="#change_phones" id="' . ("phones_change" . $row->id) .'" data-id="' . $row->id . '" data-phone="' . $row->phone . '" data-group_id="' . $row->group_id . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $phones = PhoneReceive::all();
        $groups = PhoneGroup::all();

        return view('admin.distribution.phone', [
            'phones' => $phones,
            'groups' => $groups
        ]);
    }

    public function addPhone(CreatePhoneDistributionRequest $request)
    {
        PhoneReceive::create([
            'phone' => $request->phone,
            'group_id' => $request->group_id === '0' ? null : $request->group_id,
            'is_receive' => true
        ]);

        return response()->json([
            'message' => 'Телефон успешно создан'
        ], 200);
    }

    public function editPhone(UpdatePhoneDistributionRequest $request)
    {
        $phone = PhoneReceive::find($request->id);
        if(empty($phone)){
            return response()->json([
                'message' => 'Телефон не найден'
            ], 404);
        }
        $phone->phone = $request->phone;
        $phone->group_id = $request->group_id === '0' ? null : $request->group_id;
        $phone->save();

        return response()->json([
            'message' => 'Телефон успешно обновлен'
        ], 200);
    }

    public function deletePhone(Request $request)
    {
        $status = $request->status;

        if ($status == 0) {
            if ($request->checked != 0) {

                foreach ($request->checked as $catId) {
                    $email = PhoneReceive::where('id', (int)$catId)->delete();
                }
                return response()->json([
                    'accepted' => 'Эмейлы успешно удалены'
                ]);
            }

            return response()->json([
                'error' => "Выберите хотя бы 1 эмейл"
            ]);
        }

        if ($status == 1) {
            if ($request->checked != 0) {
                $values = [];
                foreach ($request->checked as $catId) {
                    $phone = PhoneReceive::where('id', (int)$catId)->first();

                    if ($phone != null) {

                        $phone->delete();
                    }
                }

                if (count(PhoneReceive::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                return response()->json([
                    'accepted' => 'Телефоны успешно удалены',
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'error' => "Выберите хотя бы 1 телефон"
                ]);
            }
        }
    }

    public function addPhoneGroup(CreateGroupDistributionRequest $request)
    {
        PhoneGroup::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Группа успешно создана'
        ], 200);
    }

    public function editPhoneGroup(UpdateGroupDistributionRequest $request)
    {
        $group = PhoneGroup::find($request->id);
        if(empty($group)){
            return response()->json([
                'message' => 'Группа не найден'
            ], 404);
        }
        $group->name = $request->name;
        $group->save();

        return response()->json([
            'message' => 'Группа успешно обновлена'
        ], 200);
    }

    public function deletePhoneGroup(Request $request)
    {
        $status = $request->status;

        if ($status == 0) {
            if ($request->checked != 0) {

                foreach ($request->checked as $catId) {
                    $group = PhoneGroup::where('id', (int)$catId)->delete();
                }
                return response()->json([
                    'accepted' => 'Группы успешно удалены'
                ]);
            }

            return response()->json([
                'error' => "Выберите хотя бы 1 группу"
            ]);
        }

        if ($status == 1) {
            if ($request->checked != 0) {
                $values = [];
                foreach ($request->checked as $catId) {
                    $group = PhoneGroup::where('id', (int)$catId)->first();

                    if ($group != null) {

                        $group->delete();
                    }
                }

                if (count(PhoneGroup::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                return response()->json([
                    'accepted' => 'Группы успешно удалены',
                    'status' => true,
                ]);
            } else {
                return response()->json([
                    'error' => "Выберите хотя бы 1 группу"
                ]);
            }
        }
    }

    public function groupList()
    {
        $groups = EmailGroup::all();

        return Datatables::of($groups)
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" data-toggle="modal" data-target="#change_groups" id="' . ("groups_change" . $row->id) .'" data-id="' . $row->id . '" data-name="' . $row->name . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function groupPhoneList()
    {
        $groups = PhoneGroup::all();

        return Datatables::of($groups)
            ->editColumn('name', function ($row) {
                return $row->name;
            })
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" data-toggle="modal" data-target="#change_groups" id="' . ("groups_change" . $row->id) .'" data-id="' . $row->id . '" data-name="' . $row->name . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function sendPhones(Request $request)
    {
        $groupId = $request->group_id === '0' ? null : $request->group_id;
        $phones = PhoneReceive::where('group_id', $groupId)->get();

        $count = 0;
        if(!empty($phones)){
            foreach($phones as $phone){
                if(!empty($request->description)){
                    $count += 1;
                    $this->sendSMS($phone->phone, $request->description);
                } else {
                    return response()->json([
                        'message' => 'Вы не можете отправить смс с пустым текстом',
                    ], 404);
                }
            }
        } else {
            return response()->json([
                'message' => 'Не найдено ни одного телефона в группе',
            ], 404);
        }
        if($count === 0){
            return response()->json([
                'message' =>  'Смс не отправлено',
            ], 200);
        }
        return response()->json([
            'message' =>  $count > 1 ? "Вы успешно отправили ".$count." смс" : "Вы успешно отправили ".$count." смс" .'',
        ], 200);
    }

    public function sendSMS($phone, $text)
    {

        $response = Http::get('https://api.turbosms.ua/message/send.json', [
            'recipients' => [
                $phone
            ],
            'sms' => [
                'sender' => 'Biothal',
                'text' => $text,
            ],
            'token' => Env('TurboSmsToken')
        ]);
    }

    public function offer(Request $request)
    {
        $offers = DistributionOffer::all();
        if (empty($offers)) {
            return view('admin.distribution.offer', [
                'offers'
            ]);
        }

        if ($request->ajax()) {
            foreach ($offers as $key => $value){
                $offers[$key]['number'] = $key+1;
            }
            return Datatables::of($offers)
                ->editColumn('name', function ($row) {
                    return $row->name;
                })
                ->editColumn('phone', function ($row) {
                    return $row->phone;
                })
                ->editColumn('email', function ($row) {
                    return $row->email;
                })
                ->editColumn('message', function ($row) {
                    return $row->message;
                })
                ->addIndexColumn()
                ->make(true);
        }

        $offers = DistributionOffer::all();

        return view('admin.distribution.offer', [
            'offers' => $offers
        ]);
    }

    public function deleteOffer(Request $request)
    {
        $status = $request->status;

        if ($status == 0) {
            if ($request->checked != 0) {
                foreach ($request->checked as $offerId) {
                    $offer = DistributionOffer::where('id', (int)$offerId)->first();
                    $offer->delete();
                }
                return response()->json([
                    'accepted' => 'Дистрибьюторы успешно удалены'
                ]);
            }

            return response()->json([
                'error' => "Выберите хотя бы 1 дистрибьютора"
            ]);
        }
    }
}
