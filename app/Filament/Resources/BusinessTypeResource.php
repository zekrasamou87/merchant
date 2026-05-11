<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BusinessTypeResource\Pages;
use App\Filament\Resources\BusinessTypeResource\RelationManagers;
use App\Models\BusinessType;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BusinessTypeResource extends Resource
{
    protected static ?string $model = BusinessType::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

   protected static ?string $navigationLabel = 'أنواع الفعاليات';
protected static ?string $modelLabel = 'نوع الفعالية';
protected static ?string $pluralModelLabel = 'أنواع الفعاليات';
protected static ?string $navigationGroup = 'الإعدادات الإدارية';

public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')
            ->label('اسم نوع النشاط')
            ->required()
            ->unique(),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // أضيفي هذا السطر لعرض الاسم
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم نوع الفعالية')
                    ->searchable()
                    ->sortable(),
                
                // اختياري: عرض تاريخ الإضافة
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإضافة')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(), // أضفت لكِ زر الحذف الفردي أيضاً
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBusinessTypes::route('/'),
            'create' => Pages\CreateBusinessType::route('/create'),
            'edit' => Pages\EditBusinessType::route('/{record}/edit'),
        ];
    }
}
