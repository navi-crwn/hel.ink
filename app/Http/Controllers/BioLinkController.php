<?php

namespace App\Http\Controllers;

use App\Models\BioLink;
use App\Models\BioPage;
use Illuminate\Http\Request;

class BioLinkController extends Controller
{
    public function store(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);

        $validated = $request->validate([
            'type' => 'required|in:link,image,text,header,divider,spacer,video,music,vcard,html,countdown,map,faq,youtube,spotify,soundcloud,email,code',
            'title' => 'nullable|string|max:100',
            'url' => 'nullable|string|max:2000',
            'content' => 'nullable|string|max:5000',
            'description' => 'nullable|string|max:500',
            'thumbnail_url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'custom_icon' => 'nullable|string|max:500',
            'brand' => 'nullable|string|max:50',
            'btn_bg_color' => 'nullable|string|max:20',
            'btn_text_color' => 'nullable|string|max:20',
            'btn_border_color' => 'nullable|string|max:20',
            'btn_icon_invert' => 'nullable|boolean',
            'settings' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
            // Advanced block fields
            'embed_url' => 'nullable|string|max:500',
            'countdown_date' => 'nullable|date',
            'countdown_label' => 'nullable|string|max:100',
            'map_address' => 'nullable|string|max:500',
            'map_zoom' => 'nullable|integer|min:10|max:18',
            'vcard_name' => 'nullable|string|max:100',
            'vcard_phone' => 'nullable|string|max:30',
            'vcard_email' => 'nullable|email|max:100',
            'vcard_company' => 'nullable|string|max:100',
            'faq_items' => 'nullable|array',
            'faq_items.*.question' => 'nullable|string|max:500',
            'faq_items.*.answer' => 'nullable|string|max:2000',
            'html_content' => 'nullable|string|max:10000',
        ]);

        $maxOrder = $bioPage->links()->max('order') ?? 0;

        // Provide default titles for block types that don't require user-entered titles
        $defaultTitles = [
            'divider' => 'Divider',
            'spacer' => 'Spacer',
            'text' => 'Text Block',
            'html' => 'HTML Block',
            'code' => 'Code Block',
        ];
        $title = $validated['title'] ?? ($defaultTitles[$validated['type']] ?? null);

        $link = $bioPage->links()->create([
            'type' => $validated['type'],
            'title' => $title,
            'url' => $validated['url'] ?? null,
            'content' => $validated['content'] ?? null,
            'description' => $validated['description'] ?? null,
            'thumbnail_url' => $validated['thumbnail_url'] ?? null,
            'icon' => $validated['icon'] ?? null,
            'custom_icon' => $validated['custom_icon'] ?? null,
            'brand' => $validated['brand'] ?? null,
            'btn_bg_color' => $validated['btn_bg_color'] ?? null,
            'btn_text_color' => $validated['btn_text_color'] ?? null,
            'btn_border_color' => $validated['btn_border_color'] ?? null,
            'btn_icon_invert' => $validated['btn_icon_invert'] ?? false,
            'settings' => $validated['settings'] ?? null,
            'order' => $validated['order'] ?? ($maxOrder + 1),
            'is_active' => $validated['is_active'] ?? true,
            // Advanced block fields
            'embed_url' => $validated['embed_url'] ?? null,
            'countdown_date' => $validated['countdown_date'] ?? null,
            'countdown_label' => $validated['countdown_label'] ?? null,
            'map_address' => $validated['map_address'] ?? null,
            'map_zoom' => $validated['map_zoom'] ?? 15,
            'vcard_name' => $validated['vcard_name'] ?? null,
            'vcard_phone' => $validated['vcard_phone'] ?? null,
            'vcard_email' => $validated['vcard_email'] ?? null,
            'vcard_company' => $validated['vcard_company'] ?? null,
            'faq_items' => $validated['faq_items'] ?? null,
            'html_content' => $validated['html_content'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'block' => $link,
        ]);
    }

    public function update(Request $request, BioPage $bioPage, BioLink $bioLink)
    {
        $this->authorize('update', $bioPage);

        $validated = $request->validate([
            'type' => 'nullable|in:link,image,text,header,divider,spacer,video,music,vcard,html,countdown,map,faq,youtube,spotify,soundcloud,email,code',
            'title' => 'nullable|string|max:100',
            'url' => 'nullable|string|max:2000',
            'content' => 'nullable|string|max:5000',
            'description' => 'nullable|string|max:500',
            'thumbnail_url' => 'nullable|string|max:500',
            'icon' => 'nullable|string|max:50',
            'custom_icon' => 'nullable|string|max:500',
            'brand' => 'nullable|string|max:50',
            'btn_bg_color' => 'nullable|string|max:20',
            'btn_text_color' => 'nullable|string|max:20',
            'btn_border_color' => 'nullable|string|max:20',
            'btn_icon_invert' => 'nullable|boolean',
            'settings' => 'nullable|array',
            'is_active' => 'nullable|boolean',
            'order' => 'nullable|integer',
            // Advanced block fields
            'embed_url' => 'nullable|string|max:500',
            'countdown_date' => 'nullable|date',
            'countdown_label' => 'nullable|string|max:100',
            'map_address' => 'nullable|string|max:500',
            'map_zoom' => 'nullable|integer|min:10|max:18',
            'vcard_name' => 'nullable|string|max:100',
            'vcard_phone' => 'nullable|string|max:30',
            'vcard_email' => 'nullable|email|max:100',
            'vcard_company' => 'nullable|string|max:100',
            'faq_items' => 'nullable|array',
            'faq_items.*.question' => 'nullable|string|max:500',
            'faq_items.*.answer' => 'nullable|string|max:2000',
            'html_content' => 'nullable|string|max:10000',
        ]);

        // Ensure boolean fields are not null
        if (array_key_exists('btn_icon_invert', $validated) && is_null($validated['btn_icon_invert'])) {
            $validated['btn_icon_invert'] = false;
        }
        if (array_key_exists('is_active', $validated) && is_null($validated['is_active'])) {
            $validated['is_active'] = true;
        }

        $bioLink->update($validated);

        return response()->json([
            'success' => true,
            'block' => $bioLink,
        ]);
    }

    public function destroy(BioPage $bioPage, BioLink $bioLink)
    {
        $this->authorize('update', $bioPage);

        $bioLink->delete();

        return response()->json([
            'success' => true,
        ]);
    }

    public function reorder(Request $request, BioPage $bioPage)
    {
        $this->authorize('update', $bioPage);

        $validated = $request->validate([
            'links' => 'required|array',
            'links.*.id' => 'required|exists:bio_links,id',
            'links.*.order' => 'required|integer|min:0',
        ]);

        foreach ($validated['links'] as $linkData) {
            BioLink::where('id', $linkData['id'])
                ->where('bio_page_id', $bioPage->id)
                ->update(['order' => $linkData['order']]);
        }

        return response()->json([
            'success' => true,
        ]);
    }

    public function toggle(BioPage $bioPage, BioLink $bioLink)
    {
        $this->authorize('update', $bioPage);

        $bioLink->update([
            'is_active' => !$bioLink->is_active,
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $bioLink->is_active,
        ]);
    }
}
