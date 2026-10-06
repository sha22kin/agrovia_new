{{-- Styles of the admin ads list (Listing Ads and the seller profile). Rows wrap on small screens, so nothing scrolls sideways. --}}
<style type="text/css">
    .ad-tabs { display: flex; flex-wrap: wrap; gap: 6px; margin: 0; padding: 0; list-style: none; }
    .ad-tabs a { display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; border: 1px solid #e3ebf3; border-radius: 20px; font-size: 13px; color: #4a5568; background: #fff; }
    .ad-tabs a.active { background: #1ab394; border-color: #1ab394; color: #fff; }
    .ad-tabs a .num { font-weight: 700; }

    .ad-toolbar { display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; margin-bottom: 14px; }
    .ad-bulk { display: flex; gap: 6px; flex-wrap: wrap; align-items: center; }
    .ad-bulk select { width: auto; min-width: 150px; }

    .ad-list { border: 1px solid #e3ebf3; border-radius: 8px; overflow: hidden; }
    .ad-list-head { display: flex; align-items: center; gap: 10px; padding: 9px 14px; background: #f5f7fa; border-bottom: 1px solid #e3ebf3; font-size: 13px; font-weight: 600; color: #4a5568; }
    .ad-row { display: flex; flex-wrap: wrap; align-items: center; gap: 12px 14px; padding: 12px 14px; border-bottom: 1px solid #eef2f6; }
    .ad-row:last-child { border-bottom: 0; }
    .ad-row:hover { background: #fafcfd; }
    .ad-check { flex: 0 0 auto; text-align: center; font-size: 12px; color: #98a6ad; width: 30px; }
    .ad-thumb-wrap { position: relative; flex: 0 0 64px; display: block; }
    .ad-thumb-count { position: absolute; right: 3px; bottom: 3px; background: rgba(20,30,40,.7); color: #fff; font-size: 10px; border-radius: 8px; padding: 1px 5px; }
    .ad-thumb { flex: 0 0 64px; width: 64px; height: 52px; border-radius: 6px; object-fit: cover; background: #eef2f6; }
    .ad-info { flex: 1 1 260px; min-width: 0; }
    .ad-title { font-weight: 600; color: #2b3a4a; overflow-wrap: anywhere; }
    .ad-meta { display: flex; flex-wrap: wrap; gap: 4px 14px; margin-top: 4px; font-size: 12.5px; color: #7b8794; }
    .ad-meta i { color: #1ab394; margin-right: 3px; }
    .ad-reason { margin-top: 6px; padding: 6px 9px; border-left: 3px solid #ff4961; background: #fff5f6; font-size: 12.5px; color: #8a2a35; border-radius: 0 4px 4px 0; overflow-wrap: anywhere; }
    .ad-seller { flex: 0 1 190px; min-width: 150px; font-size: 13px; overflow-wrap: anywhere; }
    .ad-seller small { color: #8a96a3; display: block; }
    .ad-actions { flex: 0 0 auto; display: flex; flex-wrap: wrap; gap: 5px; margin-left: auto; }
    .ad-actions .ib { width: 34px; height: 34px; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px; border: 1px solid; background: #fff; font-size: 14px; cursor: pointer; padding: 0; transition: background .15s, color .15s; }
    .ib.view { color: #1e9ff2; border-color: #b9def9; } .ib.view:hover { background: #1e9ff2; color: #fff; }
    .ib.ok { color: #1ab394; border-color: #a9e4d6; } .ib.ok:hover { background: #1ab394; color: #fff; }
    .ib.no { color: #f6a609; border-color: #fbdca0; } .ib.no:hover { background: #f6a609; color: #fff; }
    .ib.edit { color: #666ee8; border-color: #c9ccf7; } .ib.edit:hover { background: #666ee8; color: #fff; }
    .ib.del { color: #ff4961; border-color: #ffc0c8; } .ib.del:hover { background: #ff4961; color: #fff; }

    .st { display: inline-block; padding: 2px 8px; border-radius: 10px; font-size: 11.5px; font-weight: 600; vertical-align: middle; }
    .st-active { background: #e3f7f1; color: #12896f; }
    .st-pending { background: #fff4d6; color: #a86d00; }
    .st-solded { background: #f1e6f8; color: #7b1fa2; }
    .st-cancelled { background: #ffe8eb; color: #c62839; }

    .ad-legend { display: flex; flex-wrap: wrap; gap: 4px 14px; font-size: 12px; color: #8a96a3; margin-top: 10px; }
    .ad-legend i { width: 16px; text-align: center; }

    @media (max-width: 767.98px) {
        .ad-list-head .hide-sm { display: none; }
        .ad-row { align-items: flex-start; }
        .ad-info { flex-basis: calc(100% - 130px); }
        .ad-seller { flex: 1 1 100%; padding-left: 44px; }
        .ad-actions { flex: 1 1 100%; padding-left: 44px; margin-left: 0; }
    }
</style>
