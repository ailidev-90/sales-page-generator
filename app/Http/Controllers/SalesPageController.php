<?php

namespace App\Http\Controllers;

use App\Models\SalesPage;
use App\Services\AiSalesPageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Throwable;

class SalesPageController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));

        $salesPages = SalesPage::query()
            ->whereBelongsTo($request->user())
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('product_name', 'like', '%'.$search.'%')
                        ->orWhere('generated_content->headline', 'like', '%'.$search.'%');
                });
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('sales-pages.index', [
            'salesPages' => $salesPages,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('sales-pages.form', [
            'salesPage' => new SalesPage(['template' => 'modern', 'tone' => 'professional']),
            'mode' => 'create',
        ]);
    }

    public function store(Request $request, AiSalesPageService $aiSalesPageService): RedirectResponse
    {
        $validated = $this->validated($request);

        try {
            $generated = $aiSalesPageService->generate($validated);
        } catch (Throwable) {
            return back()
                ->withInput()
                ->with('error', 'Generation failed. Please check your input and try again.');
        }

        $salesPage = $request->user()->salesPages()->create([
            ...$validated,
            'generated_content' => $generated['content'],
        ]);

        return redirect()
            ->route('sales-pages.show', $salesPage)
            ->with('success', $generated['used_fallback']
                ? 'Sales page generated with the built-in fallback because AI generation was unavailable.'
                : 'Sales page generated successfully.');
    }

    public function show(SalesPage $salesPage): View
    {
        $this->authorize('view', $salesPage);

        return view('sales-pages.show', [
            'salesPage' => $salesPage,
            'content' => $salesPage->generated_content,
        ]);
    }

    public function edit(SalesPage $salesPage): View
    {
        $this->authorize('update', $salesPage);

        return view('sales-pages.form', [
            'salesPage' => $salesPage,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, SalesPage $salesPage, AiSalesPageService $aiSalesPageService): RedirectResponse
    {
        $this->authorize('update', $salesPage);

        $validated = $this->validated($request);

        try {
            $generated = $aiSalesPageService->generate($validated);
        } catch (Throwable) {
            return back()
                ->withInput()
                ->with('error', 'Regeneration failed. Please check your input and try again.');
        }

        $salesPage->update([
            ...$validated,
            'generated_content' => $generated['content'],
        ]);

        return redirect()
            ->route('sales-pages.show', $salesPage)
            ->with('success', $generated['used_fallback']
                ? 'Sales page regenerated with fallback content because AI generation was unavailable.'
                : 'Sales page regenerated successfully.');
    }

    public function destroy(SalesPage $salesPage): RedirectResponse
    {
        $this->authorize('delete', $salesPage);

        $salesPage->delete();

        return redirect()
            ->route('sales-pages.index')
            ->with('success', 'Sales page deleted.');
    }

    public function exportHtml(SalesPage $salesPage): Response
    {
        $this->authorize('view', $salesPage);

        $html = view('sales-pages.export', [
            'salesPage' => $salesPage,
            'content' => $salesPage->generated_content,
        ])->render();

        $slug = Str::slug($salesPage->product_name) ?: 'sales-page';
        $filename = $slug.'-sales-page.html';

        return response($html, 200, [
            'Content-Type' => 'text/html',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        return $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'key_features' => ['nullable', 'string'],
            'target_audience' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'string', 'max:255'],
            'unique_selling_points' => ['nullable', 'string'],
            'tone' => ['nullable', 'string', 'in:professional,friendly,persuasive'],
            'template' => ['nullable', 'string', 'in:modern,elegant,bold'],
        ]);
    }
}
