<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProvinceResource\Pages;
use App\Filament\Resources\ProvinceResource\RelationManagers;
use App\Models\Province;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

   use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;
    protected static ?string $navigationGroup = 'التقسيمات الإدارية';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

// تعريب القائمة الجانبية
    protected static ?string $navigationLabel = 'المحافظات';
    protected static ?string $modelLabel = 'محافظة';
    protected static ?string $pluralModelLabel = 'المحافظات';
    


public static function form(Form $form): Form
{
    return $form
        ->schema([
            // الجزء العلوي: اسم المحافظة
            Forms\Components\Section::make('معلومات المحافظة')
                ->schema([
                    TextInput::make('name')
                        ->label('اسم المحافظة')
                        ->required()
                        ->unique(ignoreRecord: true),
                ]),

            // الجزء السفلي: إضافة المناطق التابعة لها
           Forms\Components\Section::make('المناطق التابعة')
    ->description('أضف المناطق التابعة لهذه المحافظة مباشرة من هنا (اختياري)')
    ->schema([
        Repeater::make('districts') 
            ->relationship('districts') // تأكدي أن اسم العلاقة في موديل Province هو districts
            ->schema([
                TextInput::make('name')
                    ->label('اسم المنطقة')
                    ->placeholder('مثلاً: جبلة، الحفة، القرداحة...')
                    ->required() // مطلوب فقط "في حال" قمتِ بالضغط على زر إضافة منطقة
                    ->maxLength(255),
            ])
            ->grid(2) 
            ->label('قائمة المناطق')
            ->addActionLabel('إضافة منطقة جديدة') 
            ->reorderable(true) 
            ->collapsible()
            ->cloneable() // ميزة إضافية: تسمح لكِ بنسخ اسم المنطقة بضغطة زر
            ->default([]) // يجعل القائمة فارغة افتراضياً عند إضافة محافظة جديدة
            ->minItems(0), // يسمح بحفظ المحافظة حتى لو كان عدد المناطق صفر
    ]),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('اسم المحافظة')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('districts_count')->label('عدد المناطق')->counts('districts'),
        ]);
}

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProvinces::route('/'),
            'create' => Pages\CreateProvince::route('/create'),
            'edit' => Pages\EditProvince::route('/{record}/edit'),
        ];
    }
}
