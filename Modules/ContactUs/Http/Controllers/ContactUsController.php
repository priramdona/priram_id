<?php

namespace Modules\ContactUs\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Modules\ContactUs\Entities\ContactUs;

class ContactUsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = ContactUs::orderBy('created_at', 'desc')->get();
        return view('contactus::index', compact('contacts'));
    }
    public function terms()
    {
        return view('contactus::terms');
    }
    public function privacy()
    {
        return view('contactus::privacy');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('contactus::create');
    }
    public function aboutUs() {
        return view('contactus::aboutus');
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'message' => 'required|string',
        ]);

        // Menyimpan kontak
        $contact = ContactUs::create($request->only('category', 'name', 'email', 'message'));

        // Kirim email ke pengirim dan grup kontak
        $recipientGroup = 'kontakkami@priram.com';
        $emailData = [
            'name' => $contact->name,
            'email' => $contact->email,
            'contactMessage' => $contact->message, // Ganti nama variabel
            'category' => $contact->category,
        ];

        Mail::send('contactus::emails.contactus', $emailData, function ($message) use ($contact, $recipientGroup) {
            $message->to($contact->email)
                ->cc($recipientGroup)
                ->subject('Terima Kasih telah Menghubungi Kami');
        });

        // Ubah status menjadi Dikirim setelah disimpan
    $contact->update(['status' => 'Dikirim']);

        return redirect()->route('contacts.index')->with('success', __('contacts.success_message'));
   }

    /**
     * Show the specified resource.
     */
    public function show(ContactUs $contact)
    {
        return view('contactus::show', compact('contact'));
    }
    public function closeInactive()
    {
        ContactUs::where('status', 'Dibalas')
            ->where('updated_at', '<', Carbon::now()->subDays(2))
            ->update(['status' => 'Ditutup']);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('contactUs::edit');
    }

    public function reply(ContactUs $contact, Request $request)
    {
        $request->validate(['message' => 'required|string']);

        // Update status menjadi Dibalas oleh admin
        $contact->update(['status' => 'Dibalas', 'message' => $request->message]);

        // Kirim balasan ke pengirim
        Mail::send('emails.reply', [
            'contact' => $contact,
            'reply' => $request->message
        ], function ($message) use ($contact) {
            $message->to($contact->email)
                ->subject('Balasan dari Admin');
        });

        return redirect()->route('contacts.show', $contact)->with('success', 'Your reply has been sent.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
