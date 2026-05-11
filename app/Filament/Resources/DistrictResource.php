<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistrictResource\Pages;
use App\Filament\Resources\DistrictResource\RelationManagers;
use App\Models\District;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistrictResource extends Resource
{
    protected static ?string $model = District::class;
    protected static ?string $navigationGroup = 'التقسيمات الإدارية';

    protected static bool $shouldRegisterNavigation = false;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';


    protected static ?string $navigationLabel = 'المناطق';
    protected static ?string $modelLabel = 'منطقة';
    protected static ?string $pluralModelLabel = 'المناطق';
   public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Select::make('province_id')
                ->label('المحافظة')
                ->relationship('province', 'name') // يفترض وجود علاقة province في موديل District
                ->required(),
            Forms\Components\TextInput::make('name')
                ->label('اسم المنطقة')
                ->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->label('المنطقة')->searchable(),
            Tables\Columns\TextColumn::make('province.name')->label('المحافظة')->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('province_id')
                ->label('فلترة حسب المحافظة')
                ->relationship('province', 'name'),
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
            'index' => Pages\ListDistricts::route('/'),
            'create' => Pages\CreateDistrict::route('/create'),
            'edit' => Pages\EditDistrict::route('/{record}/edit'),
        ];
    }
}
