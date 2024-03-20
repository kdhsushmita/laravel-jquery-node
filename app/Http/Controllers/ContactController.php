<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Company;

class ContactController extends Controller
{
    
    public function index(){
        $companyId = request('company_id');
        $companies = Company::orderBy('name')->pluck('name','id')->prepend('All Companies', '');
        $contacts = Contact::orderBy('first_name', 'desc')->where(function($query) use ($companyId) {
            if($companyId) {
                $query->where('company_id', $companyId);
            }
        })->paginate(10);
    
        return view('contacts.index', compact('contacts', 'companies'));
    }
    
    public function store(ContactRequest $request)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'requiredemail',
            'address'=>'required',
            'company_id'=>'required|exists:companies,id',
        ]);
        Contact::create($request->all());

        return redirect()->route('contacts.index'->with('message',"Contact has been added successfully"));
    }

    public function create(){
        $contact = new Contact();
        $companies = Company::orderBy('name')->pluck('name','id')->prepend('All companies','');
        return view('contacts.create',compact('companies','contact'));
    }
    

    public function show($id){
        $contact = Contact::find($id);
        return view('contacts.show',compact('contact'));
    }

    public function edit($id){
        $contact = Contact::find($id);
        if (!$contact) {
            // Handle the case where the contact doesn't exist
            abort(404); // Or any other appropriate action like redirecting with an error message
        }
        $companies = Company::orderBy('name')->pluck('name', 'id')->prepend('All companies','');
        return view('contacts.edit', compact('contact', 'companies'));
    }
    
    public function update(ContactRequest $request, Contact $contact)
    {
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'requiredemail',
            'address'=>'required',
            'company_id'=>'required|exits:companies,id',
        ]);
        Contact::update($request->all());

        return redirect()->route('contacts.index'->with('message',"Contact has been updated successfully"));
    }

    public function destroy(Contact $contact)
    {
        $contact->delete();
        $redirect = request()->query('redirect');
        return ($redirect ? redirect()->route($redirect) : back())
            ->with('message', 'Contact has been moved to trash.');
    }
}
