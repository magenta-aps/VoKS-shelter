<?php

/**
 * BComeSafe, http://bcomesafe.com
 * Copyright 2015 Magenta ApS, http://magenta.dk
 * Licensed under MPL 2.0, https://www.mozilla.org/MPL/2.0/
 * Developed in co-op with Baltic Amadeus, http://baltic-amadeus.lt
 */

namespace BComeSafe\Http\Controllers\Admin;

use BComeSafe\Http\Requests\SaveFAQFileRequest;
use BComeSafe\Models\Faq;
use BComeSafe\Models\FaqDefault;
use BComeSafe\Models\HelpFile;
use BComeSafe\Models\HelpFileDefault;
use Illuminate\Http\Request;

class HelpController extends BaseController
{
    public function getIndex()
    {
        $file = HelpFile::where('school_id', '=', \Shelter::getID())->first();

        if (!$file) {
            $file = HelpFile::firstOrNew(
                [
                'name' => '',
                'file_url' => '',
                'file_path' => '',
                'description' => '',
                'police_file_url' => '',
                'police_file_path' => '',
                'police_name' => '',
                ]
            );
        }

        return view(
            'admin.help.index',
            [
            'file' => $file,
            'defaults' => HelpFileDefault::getDefaults()
            ]
        );
    }

    public function getList()
    {
        return Faq::where('school_id', '=', \Shelter::getID())->orderBy('order', 'asc')->get();
    }

    public function moveFile($file)
    {
        $path = "uploads/help-files/";
        $file_dir = public_path($path);
        $file_path = $file_dir . sha1($file->getClientOriginalName()) . '.pdf';

        if (!\File::isDirectory($file_dir)) {
            \File::makeDirectory($file_dir);
        }

        \File::put($file_path, \File::get($file));

        $fileDetails = [
            'file_path' => $file_path,
            'name' => $file->getClientOriginalName(),
            'file_url' => url($path . sha1($file->getClientOriginalName()) . '.pdf')
        ];

        return $fileDetails;
    }

    public function postSaveFile(SaveFAQFileRequest $request)
    {
        $file = $request->file('file');
        $file2 = $request->file('file2');

        $fileDetails = [];

        if ($file) {
            $fileDetails = $this->moveFile($file);
        }

        if ($file2) {
            $moved = $this->moveFile($file2);
            $fileDetails = array_merge(
                $fileDetails,
                [
                'police_file_path' => $moved['file_path'],
                'police_file_url' => $moved['file_url'],
                'police_name' => $file2->getClientOriginalName()
                ]
            );
        }

        $fileDetails['type'] = 'pdf';
        $fileDetails['description'] = $request->get('description');
        $fileDetails['school_id'] = \Shelter::getID();

        \Session::flash('status', \Lang::get('toast.contents.admin.faq.settings_saved'));

        HelpFile::findAndUpdate(['id' => $request->get('id')], $fileDetails);

        return back();
    }

    public function postSaveFaqItem(Request $request)
    {
        $data = $request->only(['id', 'question', 'answer', 'order', 'visible']);
        $data['school_id'] = \Shelter::getID();
        if ($data['id']) {
            $item = Faq::find($data['id']);
            $item->update($data);
        } else {
            $item = Faq::create($data);
        }

        return $item;
    }

    public function postSaveVisibility(Request $request)
    {
        $data = $request->only(['id', 'visible']);
        $data['school_id'] = \Shelter::getID();
        if ($data['id']) {
            $item = Faq::find($data['id']);
            $item->update($data);
        }

        return $item;
    }

    public function postRemoveFaqItem(Request $request)
    {
        $item = Faq::find($request->get('id'));
        if ($item) {
            $item->delete();
        }
        return $item;
    }

    public function postSaveFaqOrder(Request $request)
    {
        $list = $request->all();

        foreach ($list as $position => $id) {
            if ($id) {
                Faq::find($id)->update(['order' => $position]);
            }
        }
    }

    public function getReset()
    {
        Faq::where('school_id', '=', \Shelter::getID())->delete();

        $defaults = FaqDefault::all();
        foreach ($defaults as $item) {
            $item = $item->toArray();
            $item['school_id'] = \Shelter::getID();

            Faq::create($item);
        }
    }
}
