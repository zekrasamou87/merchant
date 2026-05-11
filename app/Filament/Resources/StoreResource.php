<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StoreResource\Pages;
use App\Models\Store;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Dotswan\MapPicker\Fields\Map;

class StoreResource extends Resource
{
    protected static ?string $model = Store::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
    protected static ?string $navigationLabel = 'المحلات التجارية';
    protected static ?string $modelLabel = 'محل تجاري';
    protected static ?string $pluralModelLabel = 'المحلات التجارية';

    public static function form(Form $form): Form
    {
        return $form->schema([
            // القسم الأول: المعلومات الأساسية
            Forms\Components\Section::make('معلومات المحل الأساسية')
                ->schema([
                    Forms\Components\TextInput::make('store_name')
                        ->label('اسم المحل')
                        ->required(),
                    Forms\Components\TextInput::make('owner_name')
                        ->label('اسم صاحب المحل')
                        ->required(),
                    Forms\Components\TextInput::make('manager_name')
                        ->label('اسم المدير'),
                    Forms\Components\Select::make('business_type_id')
                        ->label('نوع النشاط')
                        ->relationship('businessType', 'name') // تأكد من مطابقة اسم العلاقة في الموديل
                        ->required()
                        ->preload()
                        ->searchable(),
                    Forms\Components\Select::make('sale_type')
                        ->label('نوع البيع')
                        ->options(['wholesale' => 'جملة', 'retail' => 'مفرق'])
                        ->default('retail'),
                    Forms\Components\TextInput::make('license_number')
                        ->label('رقم الترخيص/السجل')
                        ->required(),
                ])->columns(2),

            // القسم الثاني: الموقع ومواعيد العمل
            Forms\Components\Section::make('الموقع وتفاصيل الدوام')
                ->schema([
                    Forms\Components\Select::make('province_id')
                        ->label('المحافظة')
                        ->relationship('provinceRelation', 'name')
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(fn ($set) => $set('district_id', null)),

                    Forms\Components\Select::make('district_id')
                        ->label('المنطقة')
                        ->relationship('districtRelation', 'name', 
                            fn ($query, $get) => $query->where('province_id', $get('province_id')) 
                        )
                        ->required()
                        ->searchable()
                        ->preload(),

                    Forms\Components\TextInput::make('neighborhood')->label('الحي/الشارع')->required(),
                    Forms\Components\TextInput::make('phone_number')->label('رقم الهاتف')->tel(),

                    // --- إضافات مواعيد العمل الجديدة ---
                    Forms\Components\Toggle::make('is_always_open')
                        ->label('يعمل على مدار الساعة (24/7)')
                        ->reactive()
                        ->default(false),

                    Forms\Components\Select::make('off_days')
                        ->label('أيام العطلة')
                        ->multiple()
                        ->options([
                            'Friday' => 'الجمعة',
                            'Saturday' => 'السبت',
                            'Sunday' => 'الأحد',
                            'Monday' => 'الاثنين',
                            'Tuesday' => 'الثلاثاء',
                            'Wednesday' => 'الأربعاء',
                            'Thursday' => 'الخميس',
                        ])
                        ->visible(fn ($get) => !$get('is_always_open')),

                    Forms\Components\TimePicker::make('opening_time')
                        ->label('وقت الافتتاح')
                        ->visible(fn ($get) => !$get('is_always_open')),

                    Forms\Components\TimePicker::make('closing_time')
                        ->label('وقت الإغلاق')
                        ->visible(fn ($get) => !$get('is_always_open')),

                    Forms\Components\Toggle::make('delivery_service')
                        ->label('خدمة توصيل')
                        ->default(false),
                ])->columns(2),

            // القسم الثالث: حالة المبادرة
            Forms\Components\Section::make('حالة المبادرة (تحدث آلياً بعد الزيارة)')
                ->description('هذه البيانات يتم تحديثها تلقائياً بناءً على نتائج الكشف الميداني')
                ->schema([
                    Forms\Components\TextInput::make('rating')
                        ->label('التقييم الحالي')
                        ->disabled()
                        ->suffix('%')
                        ->placeholder('لم يتم التقييم بعد'),
                    Forms\Components\DatePicker::make('status_start_date')->label('تاريخ التفعيل')->disabled(),
                    Forms\Components\DatePicker::make('status_end_date')->label('تاريخ الانتهاء')->disabled(),
                    Forms\Components\Toggle::make('is_active')->label('حالة النشاط')->disabled(),
                ])->columns(2),

            // القسم الرابع: الخريطة
            Forms\Components\Section::make('الموقع على الخريطة')
                ->schema([
                    Map::make('location')
                        ->label('حدد الموقع')
                        ->columnSpanFull()
                        ->afterStateUpdated(function (Forms\Set $set, ?array $state): void {
                            $set('latitude', $state['lat'] ?? null);
                            $set('longitude', $state['lng'] ?? null);
                        })
                        ->defaultLocation(latitude: 35.5312, longitude: 35.7921)
                        ->zoom(15),
                    Forms\Components\TextInput::make('latitude')->label('خط العرض')->numeric()->readOnly(),
                    Forms\Components\TextInput::make('longitude')->label('خط الطول')->numeric()->readOnly(),
                ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('store_name')->label('اسم المحل')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('businessType.name')->label('النشاط')->sortable(),
                
                // عمود الحالة الآن (مفتوح/مغلق)
                Tables\Columns\TextColumn::make('is_open_now')
                    ->label('الحالة الآن')
                    ->state(fn (Store $record): string => $record->is_open_now ? 'مفتوح' : 'مغلق')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'مفتوح' => 'success',
                        'مغلق' => 'danger',
                        default => 'gray',
                    }),

                Tables\Columns\IconColumn::make('is_active')->label('نشط')->boolean(),
                Tables\Columns\TextColumn::make('rating')
                    ->label('التقييم')
                    ->formatStateUsing(fn ($state) => $state ? $state . '%' : 'N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('qr_code')
                    ->label('رمز QR')
                    ->badge()
                    ->color('success')
                    ->copyable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('province_id')->label('المحافظة')->relationship('provinceRelation', 'name'),
                Tables\Filters\SelectFilter::make('business_type_id')->label('نوع النشاط')->relationship('businessType', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('print_qr')
                    ->label('QR')
                    ->icon('heroicon-o-printer')
                    ->url(fn (Store $record) => route('print.qr', $record))
                    ->openUrlInNewTab(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array { return []; }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStores::route('/'),
            'create' => Pages\CreateStore::route('/create'),
            'edit' => Pages\EditStore::route('/{record}/edit'),
        ];
    }
}